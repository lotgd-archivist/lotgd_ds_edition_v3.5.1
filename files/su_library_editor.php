<?php
/* author: bibir (logd_bibir@email.de)
*      and Chaosmaker (webmaster@chaosonline.de)
*      for http://logd.chaosonline.de
*
*     a library with text from users to help other
* 	  a bit like faq
*
* details:
*  (15.11.04) start of idea
*  (15.01.05) project finished
*/
/*
CREATE TABLE lib_themes (
themeid int(10) unsigned NOT null auto_increment,
theme varchar(100) default null,
listorder INT(10) UNSIGNED DEFAULT '1' NOT null,
PRIMARY KEY (themeid)
) TYPE=MyISAM;


CREATE TABLE lib_books (
bookid int(10) unsigned NOT null auto_increment,
themeid int(10) default null,
acctid int(10) unsigned NOT null default '0',
author varchar(60) NOT null,
title varchar(100) default null,
book text default null,
activated enum('0','1') NOT null default '0',
listorder INT(10) UNSIGNED DEFAULT '1' NOT null,
PRIMARY KEY (bookid),
KEY themeid (themeid)
) TYPE=MyISAM;

*/

require_once 'common.php';
if (!isset($_GET['op']))
{
	$_GET['op'] = '';
}

// settings
$int_maxdp = getsetting('libdp','5');
$str_filename=basename(__FILE__);


grotto_nav();
addnav('Bibliotheksfunktionen');
page_header('Bibliothek-Editor');
output('`c`b`9Editor für Drachen-Bibliothek`0`b`c`n`n');


switch ($_GET['op'])
{
	case 'theme':
	{ //Bücher eines Themengebietes anzeigen
		addnav('Zur Themenübersicht','su_library_editor.php');
		$sql = 'SELECT theme FROM lib_themes WHERE themeid='.$_GET['themeid'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		
		if (!empty($_GET['saveorder']))
		{
			asort($_POST['order']);
			$keys = array_keys($_POST['order']);
			$i = 0;
			foreach ($keys AS $key)
			{
				$i++;
				$sql = 'UPDATE lib_books SET listorder="'.$i.'" WHERE bookid="'.$key.'"';
				db_query($sql);
			}
		}
		$str_output.='Alle Bücher zum Thema: '.$row['theme'].'`0`n`n';
		$sql = 'SELECT bookid, title, acctid, author, activated, listorder, recommended, views, show_author 
			FROM lib_books
			WHERE themeid='.$_GET['themeid'].' 
			ORDER BY listorder ASC';
		$result = db_query($sql);
		$str_output.='<form action="su_library_editor.php?op=theme&amp;themeid='.$_GET['themeid'].'&amp;saveorder=1" method="post">';
		addnav('','su_library_editor.php?op=theme&themeid='.$_GET['themeid'].'&saveorder=1');
		$str_output.='<table cellpadding=2 cellspacing=1 bgcolor="#999999" align="center">
		<tr class="trhead">
		<th>Titel</th><th>Autor</th><th>Aktiv</th><th>Empf.</th><th>Views</th><th>Sortier.</th>
		</tr>';
		if (db_num_rows($result)==0)
		{
			$str_output.='<tr class="trdark"><td colspan=6>Es sind keine Bücher vorhanden</td></tr>';
		}
		else
		{
			$bgclass = '';
			while ($row = db_fetch_assoc($result))
			{
				$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
				$str_output.='<tr class="'.$bgclass.'">
				<td><a href="su_library_editor.php?op=edit_post&id='.$row['bookid'].'">'.$row['title'].'`0</a></td>
				<td>'.($row['show_author']!=0?$row['author']:getsetting("lib_alternative_author","Allgemeine Ver&ouml;ffentlichung")).'`0</td>
				<td>'.($row['activated']==1 ? 'Ja':'Nein').'</td>
				<td>'.($row['recommended'] ? 'Ja':'Nein').'</td>
				<td>'.$row['views'].'</td>
				<td>';
				$order_options = '';
				for ($i=1; $i<=db_num_rows($result); $i++)
				{
					$order_options .= '<option value="'.$i.'"'.($i==$row['listorder']?' selected="selected"':'').'>'.$i.'</option>';
				}
				$str_output.='<select name="order['.$row['bookid'].']">'.$order_options.'</select></td>
				</tr>';
				addnav('','su_library_editor.php?op=edit_post&id='.$row['bookid']);
			}
			$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
			$str_output.='<tr class='.$bgclass.'>
			<td colspan="6" style="text-align:right"><input type="submit" class="button" value="Sortierung speichern!" /></td>
			</tr>';
		}
		$str_output.='</table></form>';
		break;
	}
		
	case 'new_theme':
	{ //neues Themengebiet erstellen
		addnav('Zur Themenübersicht','su_library_editor.php');
		if ($_GET['subop']=='save')
		{
			$sql = 'SELECT MAX(listorder) AS maxtheme FROM lib_themes';
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$row['maxtheme']++;
			$sql = "INSERT INTO lib_themes (theme,listorder) VALUES ('".$_POST['theme']."',".$row['maxtheme'].")";
			$result = db_query($sql);
			$str_output.='`@Neues Thema wurde angelegt.`0`n`n';
		}
		$str_output.='<form action="su_library_editor.php?op=new_theme&subop=save" method="POST">
		<table>
		<tr><td>Thema </td>
		<td><input name="theme" maxlength="100"></td>
		</tr>
		</table>
		<input type="submit" class="button" value="Speichern">
		</form>
		`n`bVorhandene Themen:`b
		`n`n';
		addnav('','su_library_editor.php?op=new_theme&subop=save');
		$sql = 'SELECT listorder,theme FROM lib_themes ORDER BY listorder ASC, themeid ASC';
		$result = db_query($sql);
		if (db_num_rows($result)==0)
		{
			$str_output.='Es gibt keine Themen.';
		}
		else while ($row = db_fetch_assoc($result))
		{
			$str_output.=$row['listorder'].' - '.$row['theme'].'`0`n';
		}
		break;
	}
		
	case 'edit_theme':
	{ //Themengebiet bearbeiten
		addnav('Zur Themenübersicht','su_library_editor.php');
		if ($_GET['subop']=='save')
		{
			$_POST['theme'] = db_real_escape_string(closetags(stripslashes($_POST['theme']),'`i`b`c`H'));
			$sql = "UPDATE lib_themes SET theme='".$_POST['theme']."', description='".$_POST['description']."' WHERE themeid=".$_GET['themeid'];
			$result = db_query($sql);
			//output("Thema wurde geändert.`n`n");
			redirect('su_library_editor.php?op=browse');
		}
		else
		{
			$sql = 'SELECT theme, description FROM lib_themes WHERE themeid='.$_GET['themeid'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$str_output.="Hier kann das Thema geändert werden.
			<form action=\"su_library_editor.php?op=edit_theme&subop=save&themeid=".$_GET['themeid']."\" method='POST'>
			Thema:`n<input type='text' name='theme' value='".utf8_htmlentities(str_replace("`","``",$row['theme']),ENT_QUOTES)."' maxlength='100' size='80'>`n
			Beschreibung:`n<textarea name='description' class='input' cols='50' rows='10'>".utf8_htmlentities(str_replace("`","``",$row['description']),ENT_QUOTES)."</textarea>`n
	`n<input type='submit' class='button' value='Speichern'></form>";
			addnav('','su_library_editor.php?op=edit_theme&subop=save&themeid='.$_GET['themeid']);
		}
		break;
	}
		
	case 'del_theme':
	{ //Themengebiet löschen
		addnav('Zur Themenübersicht','su_library_editor.php');
		//buecher, die zu diesem thema gehoeren:
		//a) mitloeschen
		//b) anderem Thema zuordnen
		//c) auf themeid 0 setzen
		$sql = 'SELECT COUNT(*) AS anz FROM lib_books WHERE themeid='.$_GET['themeid'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		if ($row['anz']==0)
		{
			$str_output.='Es sind keine Bücher zu diesem Thema vorhanden, das Thema wird gelöscht.';
			$sql = 'DELETE FROM lib_themes WHERE themeid='.$_GET['themeid'];
			db_query($sql);
		}
		else
		{
			$str_output.="Es sind ".$row['anz']." Bücher vorhanden, was soll mit diesen passieren?`n`n
			<form action=\"su_library_editor.php?op=del_theme2&themeid=".$_GET['themeid']."\" method='POST'>
	<input type='radio' name='del' value='del_choice'>ebenfalls löschen`n
	<input type='radio' name='del' value='other_theme'>einem anderen Thema zuordnen`n
	<input type='radio' name='del' value='no_theme'>keinem Thema zuordnen`n
	<input type='submit' class='button' value='Löschen'></form>";
			addnav('','su_library_editor.php?op=del_theme2&themeid='.$_GET['themeid']);
		}
		break;
	}
		
	case 'del_theme2':
	{
		addnav('Zur Themenübersicht','su_library_editor.php');
		if ($_POST['del']=='del_choice')
		{
			$sql = 'DELETE FROM lib_books WHERE themeid='.$_GET['themeid'];
			db_query($sql);
			$sql = 'DELETE FROM lib_themes WHERE themeid='.$_GET['themeid'];
			db_query($sql);
			$str_output.='Bücher und Thema gelöscht.';
		}
		else if ($_POST['del']=='other_theme')
		{
			$str_output.='Folgende Bücher einem anderen Thema zuordnen:`n';
			$sql = 'SELECT title FROM lib_books WHERE themeid='.$_GET['themeid'];
			$result= db_query($sql);
			while ($row = db_fetch_assoc($result))
			{
				$str_output.=$row['title'].'`0`n';
			}
			output("`nWelches Thema sollen die Bücher nun haben?");
			$sql = "SELECT * FROM lib_themes WHERE themeid!=".$_GET['themeid'];
			$result = db_query($sql);
			$str_output.='<form action="su_library_editor.php?op=del_theme3&old_themeid='.$_GET["themeid"].'" method="POST">
			<select name="new_themeid">';
			while ($row = db_fetch_assoc($result))
			{
				$str_output.='<option value="'.$row['themeid'].'">';
				$str_output.=utf8_preg_replace('/`./','',$row['theme']);
				$str_output.='</option>';
			}
			$str_output.='</select>
			<input type="submit" class="button" value="Thema zuordnen"></form>';
			addnav('','su_library_editor.php?op=del_theme3&old_themeid='.$_GET['themeid']);
		}
		else
		{
			$sql = 'UPDATE lib_books SET themeid=0 WHERE themeid='.$_GET['themeid'];
			db_query($sql);
			$sql = 'DELETE FROM lib_themes WHERE themeid='.$_GET['themeid'];
			db_query($sql);
			$str_output.="Thema-ID der Bücher entfernt und Thema gelöscht.";
		}
		break;
	}
		
	case 'del_theme3':
	{
		addnav('Zur Themenübersicht','su_library_editor.php');
		$sql = 'UPDATE lib_books SET themeid="'.$_POST['new_themeid'].'" WHERE themeid='.$_GET['old_themeid'] ;
		db_query($sql);
		$sql = 'DELETE FROM lib_themes WHERE themeid='.$_GET['old_themeid'];
		db_query($sql);
		$str_output.='Bücher neu zugeordnet und das Thema gelöscht.';
		break;
	}
		
	case 'new_books':
	{ //Liste neue Bücher, aktivieren/deaktivieren
		addcommentary(false);
		
		addnav('Zur Themenübersicht','su_library_editor.php');
		
		if ($_GET['subop']=='activate')
		{
			
			$str_output.='<hr>`n';
			
			$sql = 'SELECT lib_books.acctid, lib_books.title, accounts.login FROM lib_books LEFT JOIN accounts USING(acctid) WHERE bookid='.$_GET['id'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			if($row['login']=='')
			{
				$row['login']='`4gelöscht`0';
			}
			
			$str_output.='Buch: `b'.$row['title'].'`0 von '.$row['login'].'`b freischalten`0`n`n';
			
			$dpts4book = (int)$_POST['dp'];
			$dpts4book = min($int_maxdp,$dpts4book);
			$dpts4book = max(0,$dpts4book);
			
			$int_id = (int)$_GET['id'];
			
			$str_comment = $_POST['comment'];
			
			if ($_GET['act'] == 'ok')
			{
				
				if ($session['formsec'] != '')
				{
					unset($session['formsec']);
					
					$str_output.='Dieses Buch ist jetzt für alle in der Bibliothek einsehbar. Du vergibst an den Autor '.$dpts4book.' DP!`n';
					
					$sql = 'UPDATE lib_books SET activated="1" WHERE bookid='.$int_id;
					$result = db_query($sql);
					
					user_update(
						array
						(
							'donation'=>array('sql'=>true,'value'=>'donation+'.$dpts4book)
						),
						intval($row['acctid'])
					);
					
					systemlog('Gab '.$dpts4book.' Donationpoints für Buch '.strip_appoencode($row['title'],3).' an ',$session['user']['acctid'],$row['acctid']);
					systemmail($row['acctid'],'Dein Buch wurde angenommen!','`0Das Buch mit dem Titel "'.$row['title'].'`0", das du eingereicht hattest, wurde in die Bibliothek aufgenommen. Du bekommst dafür '.$dpts4book.' Donationpoints.'.(!empty($str_comment) ? '`n'.$str_comment : '') );
					
				}
				
			}
			else
			{
				
				viewcommentary('sulib_new','sagen:',40,'sagt');
				
				$session['formsec'] = md5(time());
				
				$arr_form = array('dp'=>'DP (Max. `b'.$int_maxdp.'`b Punkte),int',
				'comment'=>'Kurze Begründung / Nachricht an den Autor (optional)');
				
				$arr_data = array('dp'=>round($int_maxdp*0.5));
				
				$str_lnk = 'su_library_editor.php?op=new_books&subop=activate&act=ok&id='.$int_id;
				addnav('',$str_lnk);
				$str_output.='Wie viele Donationpoints möchtest du dem Autor für seine Arbeit gewähren?`n
				<form method="POST" action="'.$str_lnk.'">';
				
				output($str_output);
				$str_output='';
				showform($arr_form,$arr_data,false,'Freischalten!');
				
				$str_output.='</form>`n`n';
				
			}
			
			addnav('n?Weitere neue Bücher','su_library_editor.php?op=new_books');
		}
		else if ($_GET['subop'] == 'activate_again')
		{
			$int_id = (int)$_GET['id'];
			$str_output.='Dieses Buch ist jetzt wieder für alle in der Bibliothek einsehbar.`n';
			
			$sql = 'UPDATE lib_books SET activated="1" WHERE bookid='.$int_id;
			$result = db_query($sql);
		}
		else if ($_GET['subop'] == 'deactivate')
		{
			$int_id = (int)$_GET['id'];
			$str_output.='Dieses Buch ist nun nicht mehr in der Bibliothek einsehbar, bis es wieder aktiviert wird!`n';
			
			$sql = 'UPDATE lib_books SET activated="2" WHERE bookid='.$int_id;
			$result = db_query($sql);
		}
		else
		{
			// Keine Aktion
			
			viewcommentary("sulib_new","sagen:",40,"sagt");
			
			$str_output.="Das sind die Bücher, die eingereicht, aber noch nicht freigegeben wurden:
			`n`b`^Ungelesene`0`b sind gelb hervorgehoben.
			`n`n";
			$sql = "SELECT b.bookid, b.title, t.theme, b.acctid, b.author, b.seen, b.activated, b.show_author
				FROM lib_books b
				LEFT JOIN lib_themes t USING(themeid)
				WHERE b.activated='0' OR b.activated='2'
				ORDER BY b.seen ASC";
			$result = db_query($sql);
			$str_output.='<table cellpadding=2 cellspacing=1 bgcolor="#999999" align="center">
			<tr class="trhead">
			<th>Option</th><th>ID</th><th>Thema</th><th>Titel</th><th>Autor</th>
			</tr>';
			$bgclass = '';
			if (db_num_rows($result)==0)
			{
				$str_output.="<tr class='trdark'><td colspan=5>Es gibt keine Bücher, die aktiviert werden müssten.</td></tr>";
			}
			else
			{
				while ($row = db_fetch_assoc($result))
				{
					$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
					$str_output.='<tr class="'.$bgclass.'">
					<td>'
					.($row['activated'] == '0'
					? create_lnk('Aktivieren','su_library_editor.php?op=new_books&subop=activate&id='.$row['bookid'])
					: create_lnk('Erneut Aktivieren','su_library_editor.php?op=new_books&subop=activate_again&id='.$row['bookid'])
					)
					.'|'
					.create_lnk('Löschen','su_library_editor.php?op=del_post&id='.$row['bookid'])
					.'</td>
					<td>'.(!$row['seen'] ? '`^`b~' : '-').$row['bookid'].(!$row['seen'] ? '`b~`0' : '-').'</td>
					<td>'.$row['theme'].'`0</td>
					<td><a href="su_library_editor.php?op=edit_post&id='.$row['bookid'].'">'.$row['title'].'`0</a></td>
					<td>'.($row['show_author']!=0?$row['author']:getsetting('lib_alternative_author','Allgemeine Ver&ouml;ffentlichung')).'`0</td>
					</tr>';
					
					addnav('','su_library_editor.php?op=edit_post&id='.$row['bookid']);
					//view
				}
			}
			$str_output.='</table>';
			
		}
		
		break;
	}
		
	case 'del_post':
	{ //Buch ablehnen / löschen
		$sql = 'SELECT acctid, title, activated FROM lib_books WHERE bookid='.$_GET['id'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		
		$str_output.='Buch: `b'.$row['title'].'`b löschen`0`n`n';
		
		$str_comment = $_POST['comment'];
		
		$int_id = (int)$_GET['id'];
		
		if ($_GET['act'] == 'ok')
		{
			
			$str_output.='Dieses Buch ist für immer verschwunden - und das ist auch gut so.';
			
			if ($session['formsec'] != '')
			{
				unset($session['formsec']);
				
				$sql = 'SELECT acctid, title, activated FROM lib_books WHERE bookid='.$int_id;
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				if ($row['activated']==1)
				{
					systemmail($row['acctid'],'Dein Buch wurde verbrannt!','`0Das Buch mit dem Titel "'.$row['title'].'`0", das in der Bibliothek stand, wurde im Kamin verbrannt.'.(!empty($str_comment) ? '`n'.$str_comment : ''));
				}
				else
				{
					systemmail($row['acctid'],'Dein Buch wurde abgelehnt!','`0Das Buch mit dem Titel "'.$row['title'].'`0", das du eingereicht hattest, wurde nicht in die Bibliothek aufgenommen.'.(!empty($str_comment) ? '`n'.$str_comment : ''));
				}
				
				debuglog('Biblio - Buch mit Namen "'.$row['title'].'" gelöscht');
				
				$sql = 'DELETE FROM lib_books WHERE bookid='.$_GET['id'];
				db_query($sql);
				
				
			}
			
		}
		else
		{
			
			$session['formsec'] = md5(time());
			
			$arr_form = array('comment'=>'Kurze Begründung / `nNachricht an den Autor `n(optional),textarea,50,5');
			
			$arr_data = array();
			
			$str_lnk = 'su_library_editor.php?op=del_post&act=ok&id='.$int_id;
			addnav('',$str_lnk);

			output('Buch löschen?`n
				<form method="POST" action="'.$str_lnk.'">',true);
			
			showform($arr_form,$arr_data,false,'Die Ratten warten schon!');
			
			output('</form>`n`n');
		}
		
		addnav('n?Weitere neue Bücher','su_library_editor.php?op=new_books');
		addnav('Zur Themenübersicht','su_library_editor.php');
		break;
	}
		
	case 'edit_post':
	{ //Buch bearbeiten
		$sql = 'UPDATE lib_books SET seen=1 WHERE bookid='.$_GET['id'];
		db_query($sql);
		
		if ($_GET['subop']=='button')
		{
			if (isset($_POST['save']))
			{
				
				$str_output.='Buch bearbeitet.';
				$sql="UPDATE lib_books set themeid='".$_POST['themeid']."',
					title='".$_POST['title']."',
					book='".$_POST['book']."'
					WHERE bookid=".$_GET['id'];
				db_query($sql);
			}
			else if (isset($_POST['activate']))
			{
				redirect('su_library_editor.php?op=new_books&subop=activate&id='.$_GET['id']);
			}
			else if (isset($_POST['reactivate']))
			{
				redirect('su_library_editor.php?op=new_books&subop=activate_again&id='.$_GET['id']);
			}
			else if (isset($_POST['deactivate']))
			{
				redirect('su_library_editor.php?op=new_books&subop=deactivate&id='.$_GET['id']);
			}
			else if (isset($_POST['recommend_on']))
			{
				$sql = 'UPDATE lib_books SET recommended=1 WHERE bookid='.$_GET['id'];
				db_query($sql);
				redirect('su_library_editor.php?op=edit_post&id='.$_GET['id']);
			}
			else if (isset($_POST['recommend_off']))
			{
				$sql = 'UPDATE lib_books SET recommended=0 WHERE bookid='.$_GET['id'];
				db_query($sql);
				redirect('su_library_editor.php?op=edit_post&id='.$_GET['id']);
			}
			else if (isset($_GET['notseen']))
			{
				$sql = 'UPDATE lib_books SET seen=0 WHERE bookid='.$_GET['id'];
				db_query($sql);
				redirect('su_library_editor.php?op=new_books');
			}
			else if (isset($_POST['del']))
			{
				redirect("su_library_editor.php?op=del_post&id=".$_GET['id']);
			}
			else if (isset($_POST['alterauthor']))
			{
				$sql = 'UPDATE lib_books SET show_author=0 WHERE bookid='.$_GET['id'];
				db_query($sql);
				redirect('su_library_editor.php?op=edit_post&id='.$_GET['id']);
			}
			else if (isset($_POST['author']))
			{
				$sql = 'UPDATE lib_books SET show_author=1 WHERE bookid='.$_GET['id'];
				db_query($sql);
				redirect('su_library_editor.php?op=edit_post&id='.$_GET['id']);
			}
		}
		
		$sql = 'SELECT themeid, lb.title, book, lb.activated, lb.acctid, author, recommended, show_author, 
			a.name
			FROM lib_books lb
			LEFT JOIN accounts a ON lb.acctid=a.acctid
			WHERE bookid='.$_GET['id'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		
		$str_output.='<form action="su_library_editor.php?op=edit_post&subop=button&id='.$_GET['id'].'" method="POST">
		`nThema: <select name="themeid">';
		$sql2 = 'SELECT * FROM lib_themes ORDER BY themeid ASC';
		$result2 = db_query($sql2);
		while ($row2 = db_fetch_assoc($result2))
		{
			$str_output.='<option value="'.$row2['themeid'].'" '.($row2['themeid']==$row['themeid']?' selected':'').'>'.$row2['theme'].'`0</option>';
		}
		$str_output.='</select>
		`nTitel: <input type="text" name="title" value="'.str_replace(array('`','³','²'),array('``','³³','²²'),$row['title']).'" size="50" maxlength="50">
		`nAutor: '.$row['author'].'`0 (jetzt: '.($row['name']?$row['name']:'`4gelöscht').'`0)`n';
		//Superuserfunktion ob Autor angezeigt werden soll
		if ($row['show_author'] != 0)
		{
			$str_output.="<input type='submit' class='button' name='alterauthor' value='Autor verbergen'> (Alternative Angabe ist festlegbar in den Spieleinstellungen.)";
		}
		else
		{
			$str_output.="<input type='submit' class='button' name='author' value='Autor wieder anzeigen'> (Der vollständige Name des Autors als das Buch erstellt wurde wird dann wieder angezeigt.)";
		}
		//Ende der neuen Funktion
		$str_output.='`n`nIn dem Buch steht geschrieben:
		`n<textarea name="book" class="input" cols="60" rows="10">'.str_replace(array('`','³','²'),array('``','³³','²²'),$row['book']).'</textarea>
		`n<input type="submit" class="button" name="save" value="Speichern">';
		if ($row['activated']=='0')
		{
			$str_output.="<input type='submit' class='button' name='activate' value='Aktivieren'>";
			addnav('Aktivieren','su_library_editor.php?op=new_books&subop=activate&id='.$_GET['id']);
			addnav('Ungelesen markieren','su_library_editor.php?op=edit_post&subop=button&notseen=1&id='.$_GET['id']);
		}
		elseif ($row['activated'] == '2')
		{
			$str_output.="<input type='submit' class='button' name='reactivate' value='ReAktivieren'>";
			addnav('ReAktivieren','su_library_editor.php?op=new_books&subop=activate_again&id='.$_GET['id']);
		}
		else
		{
			$str_output.="<input type='submit' class='button' name='deactivate' value='Deaktivieren'>";
			addnav('Deaktivieren','su_library_editor.php?op=new_books&subop=deactivate&id='.$_GET['id']);
		}
		if ($row['recommended'])
		{
			$str_output.='<input type="submit" class="button" name="recommend_off" value="Empfehlen AUS">';
		}
		else
		{
			$str_output.='<input type="submit" class="button" name="recommend_on" value="Empfehlen AN">';
		}
		
		$str_output.="<input type='submit' class='button' name='del' value='Löschen'></form>`n";
		addnav('','su_library_editor.php?op=edit_post&subop=button&id='.$_GET['id']);
		
		
		$str_output.='Das Buch:`n`n'.str_replace("\n",'`n',$row['book']).'`0';
		
		addnav('n?Zu neuen Büchern','su_library_editor.php?op=new_books');
		addnav('o?Zu Büchern ohne Thema','su_library_editor.php?op=no_theme_books');
		if ($row['themeid'])
		{
			addnav('T?Zum Themenbereich','su_library_editor.php?op=theme&themeid='.$row['themeid']);
		}
		addnav('Zur Themenübersicht','su_library_editor.php');
		break;
	}
		
	case 'no_theme_books':
	{ //Liste der Bücher ohne Themengebiet
		addnav('Zur Themenübersicht','su_library_editor.php');
		$sql = 'SELECT bookid, title, author, show_author FROM lib_books WHERE themeid=0';
		$result = db_query($sql);
		$str_output.='<table cellpadding=2 cellspacing=1 bgcolor="#999999" align="center">
		<tr class="trhead">
		<th>Option</th><th>ID</th><th>Titel</th><th>Autor</th>
		</tr>';
		$bgclass = '';
		if (db_num_rows($result)==0)
		{
			$str_output.='<tr class="trdark">
			<td colspan=4>Es gibt keine Bücher, die einem Thema zugeordnet werden müßten.</td>
			</tr>';
		}
		else while ($row = db_fetch_assoc($result))
		{
			$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
			$str_output.='<tr class="'.$bgclass.'">
			<td><a href="su_library_editor.php?op=del_post&id='.$row['bookid'].'">Löschen</a></td>
			<td>'.$row['bookid'].'</td>
			<td><a href="su_library_editor.php?op=edit_post&id='.$row['bookid'].'">'.$row['title'].'`0</a></td>
			<td>'.($row['show_author']!=0?$row['author']:getsetting('lib_alternative_author','Allgemeine Ver&ouml;ffentlichung')).'`0</td>
			</tr>';
			addnav('','su_library_editor.php?op=del_post&id='.$row['bookid']);
			//delete
			addnav('','su_library_editor.php?op=edit_post&id='.$row['bookid']);
			//ansehen
		}
		$str_output.='</table>';
		break;
	}	
		
	case 'guild_books':
	{
		require_once(LIB_PATH.'dg_funcs.lib.php');
		$arr_status=array('Neu','`@OK`0','`$Gesperrt`0');
		switch($_GET['act'])
		{
			case 'show':
			{ //Buch ansehen
				$sql='SELECT * FROM dg_books WHERE bookid='.$_GET['id'];
				$result=db_query($sql);
				if(db_num_rows($result)!=1)
				{
					$str_output.='`$Fehler: BuchID nicht oder mehrfach vorhanden`0';
				}
				else
				{
					$row=db_fetch_assoc($result);
					$str_output.='<table width="95%" align="center">
					<tr class="trdark">
					<td>'.$row['title'].'</td>
					<td>&copy; '.$row['author'].'</td>
					</tr>
					<tr class="trdark"><td colspan="2"><hr></td></tr>
					<tr class="trlight">
					<td colspan="2">'.nl2br($row['txt']).'</td>
					</tr>
					</table>
					<form action="'.$str_filename.'?op=guild_books&act=activ" method="post">
					Dieses Buch ist <select name="su_activate">
					<option value="0"'.($row['su_activated']==0?' selected':'').'>Neu eingereicht</option>
					<option value="1"'.($row['su_activated']==1?' selected':'').'>Aktiv</option>
					<option value="2"'.($row['su_activated']==2?' selected':'').'>Von einem Admin gesperrt</option>
					</select>
					<input type="hidden" name="id" value="'.$row['bookid'].'">
					<input type="submit" class="button" value="Setzen">
					</form>
					';
					addnav('',$str_filename.'?op=guild_books&act=activ');
					
					addnav('Autor anschreiben','mail.php?op=write&to='.$row['acctid'],false,true);
					$sql='SELECT acctid FROM accounts WHERE guildid='.$row['guildid'].' AND guildfunc=5 ORDER BY laston DESC';
					$result=db_query($sql);
					$rowg=db_fetch_assoc($result);
					addnav('Lehrmeister anschreiben','mail.php?op=write&to='.$rowg['acctid'],false,true);
					addnav('Buch löschen',$str_filename.'?op=guild_books&act=del&id='.$row['bookid'],false,false,false,false,'Soll das Buch wirklich gelöscht werden?');
					addnav('Zurück');
				}
				addnav('Zur Übersicht',$str_filename.'?op=guild_books');
				break;
			}
		
			case 'activ':
			{ //Buch aktivieren/sperren
				$sql='UPDATE dg_books SET su_activated='.(int)$_POST['su_activate'].' WHERE bookid='.(int)$_POST['id'];
				db_query($sql);
				$db_rows=db_affected_rows();
				if($db_rows==1)
				{
					redirect($str_filename.'?op=guild_books');
				}
				else
				{
					$str_output.='`$Oops, '.$db_rows.' Bücher geändert!`0`n';
				}
				break;
			}

            /** @noinspection PhpMissingBreakStatementInspection */
            case 'del':
			{ //buch löschen
				$sql='DELETE FROM dg_books WHERE bookid='.$_GET['id'];
				db_query($sql);
				$db_rows=db_affected_rows();
				if($db_rows==1)
				{
					$str_output.='`b`@Buch gelöscht`0`b`n`n';
				}
				else
				{
					$str_output.='`b`$Oops, '.$db_rows.' Bücher gelöscht!`0`b`n`n';
				}
				//kein break;
			}
		
			default:
			{ //Übersicht				
				$str_lnk = $str_filename.'?op=guild_books';
				
				
				//Suchleiste einblenden
				
				//Suchvariablen holen
				$arr_nav_vars = persistent_nav_vars(
					array(
						'search_guildid',
						'search_new',
						'search_locked',
						'search_checked'
					),
					//Variablen löschen oder nur holen
					isset($_POST['search_clear'])?true:false
				);				
				
				//Dropdownliste erstellen
				$arr_guild_list = db_get_all('SELECT guildid,name FROM dg_guilds');
				array_unshift($arr_guild_list,array('guildid'=>-1, 'name'=>'Alle anzeigen'));
				
				$str_guildlist .= '<select name="search_guildid" onchange="this.form.submit();">';
								
				foreach ($arr_guild_list as $arr_guild)
				{
					$str_selected = ($arr_nav_vars['search_guildid'] == $arr_guild['guildid'])?'selected':'';
					$str_guildlist .= '<option value="'.$arr_guild['guildid'].'" '.$str_selected.'>'.$arr_guild['name'].'</option>';
				}
				$str_guildlist .= '</select>';
				
				//Form erstellen
				$str_output .= '`c'.form_header($str_filename.'?op=guild_books');
				$str_output .= $str_guildlist;
				$str_output .= '<input type="checkbox" name="search_new" ('.($arr_nav_vars['search_new']?'checked':'').') />Neue anzeigen <input type="checkbox" name="search_locked" '.($arr_nav_vars['search_locked']?'checked':'').'/>Gesperrte anzeigen <input type="checkbox" name="search_checked" '.($arr_nav_vars['search_checked']?'checked':'').' />Freigeschaltete anzeigen';
				$str_output .= '&nbsp;<input type="submit" name="search_submit" value="Go!" />&nbsp;<input type="submit" name="search_clear" value="Clear!" />';
				$str_output .= form_footer().'`c';				
				
				if(count($arr_nav_vars) > 0)
				{
					$where = 'WHERE 1';
					if(isset($arr_nav_vars['search_guildid']) && $arr_nav_vars['search_guildid'] != -1)
					{
						$where .= ' AND b.guildid='.$arr_nav_vars['search_guildid'];
					}
					if(isset($arr_nav_vars['search_new']))
					{
						$where .= ' AND b.su_activated=0';
					}
					if(isset($arr_nav_vars['search_locked']))
					{
						$where .= ' AND b.su_activated=2';
					}
					if(isset($arr_nav_vars['search_checked']))
					{
						$where .= ' AND b.su_activated=1';
					}
				}
				
				$arr_res = page_nav($str_lnk,'SELECT COUNT(*) AS c FROM dg_books b '.$where,30);
			
				
				$sql='SELECT b.*,g.name FROM dg_books b
				LEFT JOIN dg_guilds g ON g.guildid=b.guildid
				'.$where.'
				ORDER BY b.guildid,b.su_activated,b.title
				LIMIT '.$arr_res['limit'];
				$result=db_query($sql);
				
				$guildid=-1;
				$dg_guild_themes = array();
				$str_output.='<table width="95%" border="0" cellpadding="2" cellspacing="1" bgcolor="#999999" align="center"';
				while($row=db_fetch_assoc($result))
				{
					if($row['guildid']!=$guildid)
					{ //Trennzeile neue Gilde
						$str_output.='<tr class="trdark">
						<td align="center" colspan="3"><br/>`b'.create_lnk('Bücher der Gilde '.($row['name']!=''?$row['name']:'`$gelöscht').'`0',$str_filename.'?op=guild_books&search_guildid='.$row['guildid']).'`b<br/><br/></td>						
						</tr>';
						$guildid=$row['guildid'];
						$theme='';
					}
					
					/* Erstmal auskommentiert, weil es irgendwie doof aussieht und auch keinen Mehrwert bringt
					if($row['theme']!=$theme)
					{ //Trennzeile neues Thema
						if(!isset($dg_guild_themes[$row['guildid']]))
						{
							$arr_themes = dg_load_guild($row['guildid'],array('building_vars'));
							$dg_guild_themes[$row['guildid']] = $arr_themes['building_vars']['bibliothek']['dg_book_themes'];
						}
												
						$str_output.='<tr class="trdark">
						<td colspan="3">`cBücher zum Thema '.$dg_guild_themes[$row['guildid']][$row['theme']].'`0`c</td>
						</tr>';
						$theme=$row['theme'];
					}*/
					
					$str_output.='<tr class="trlight">
					<td>'.create_lnk($row['title'],$str_filename.'?op=guild_books&id='.$row['bookid'].'&act=show').'</td>
					<td align="left">'.$row['author'].'</td>
					<td align="center">'.$arr_status[$row['su_activated']].'</td>
					</tr>';
				}
				$str_output.='</table>';
				break;
			}
		}
		addnav('Zum Editor',$str_filename);
		break;
	}
	default:
	{ //Themenübersicht
		if (!empty($_GET['saveorder']))
		{
			asort($_POST['order']);
			$keys = array_keys($_POST['order']);
			$i = 0;
			foreach ($keys AS $key)
			{
				$i++;
				$sql = 'UPDATE lib_themes SET listorder="'.$i.'" WHERE themeid="'.$key.'"';
				db_query($sql);
			}
		}
		
		$str_output.='Übersicht der Themen`n`n
		<form action="su_library_editor.php?saveorder=1" method="post">
		<table cellpadding=2 cellspacing=1 bgcolor="#999999" align="center">
		<tr class="trhead">
		<th>Option</th><th>ID</th><th>Thema</th><th>Anzahl Bücher</th><th>Sortierung</th>
		</tr>';
		addnav('','su_library_editor.php?saveorder=1');
		$sql = "SELECT t.*, COUNT(b.bookid) AS anz FROM lib_themes t
			LEFT JOIN lib_books b USING(themeid)
			GROUP BY themeid
			ORDER BY listorder ASC";
		$result = db_query($sql);
		if (db_num_rows($result)==0)
		{
			$str_output.='<tr class="trdark"><td colspan=5 align="center">`&`iEs gibt keine Themen`i`0</td></tr>';
		}
		else
		{
			$bgclass = '';
			while ($row = db_fetch_assoc($result))
			{
				$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
				$order_options = '';
				for ($i=1; $i<=db_num_rows($result); $i++)
				{
					$order_options .= '<option value="'.$i.'"'.($i==$row['listorder']?' selected="selected"':'').'>'.$i.'</option>';
				}
				$str_output.='<tr class="'.$bgclass.'">
				<td><a href="su_library_editor.php?op=edit_theme&themeid='.$row['themeid'].'">Edit</a> | <a href="su_library_editor.php?op=del_theme&themeid='.$row['themeid'].'">Löschen</a></td>
				<td>'.$row['themeid'].'</td>
				<td><a href="su_library_editor.php?op=theme&themeid='.$row['themeid'].'">'.$row['theme'].'`0</a></td>
				<td>'.$row['anz'].'</td>
				<td><select name="order['.$row['themeid'].']">
				'.$order_options.'
				</select>
				</td></tr>
				<tr class='.$bgclass.'>
				<td colspan=5>'.strip_appoencode(mb_substr($row['description'],0,60),2).'...</td>
				</tr>';
				
				addnav('','su_library_editor.php?op=edit_theme&themeid='.$row['themeid']);
				addnav('','su_library_editor.php?op=del_theme&themeid='.$row['themeid']);
				addnav('','su_library_editor.php?op=theme&themeid='.$row['themeid']);
			}
			$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
			$str_output.='<tr class='.$bgclass.'>
			<td colspan="5" style="text-align:right"><input type="submit" class="button" value="Sortierung speichern!" /></td>
			</tr>';
		}
		$str_output.='</table></form>';
		addnav('T?Neues Thema erstellen','su_library_editor.php?op=new_theme');
		addnav('Neue Bücher ansehen','su_library_editor.php?op=new_books');
		addnav('Bücher ohne Thema','su_library_editor.php?op=no_theme_books');
		//addnav('Gildenbücher');
		//addnav('Gildenbücher ansehen',$str_filename.'?op=guild_books');
	}
}

output($str_output);

page_footer();
?>

