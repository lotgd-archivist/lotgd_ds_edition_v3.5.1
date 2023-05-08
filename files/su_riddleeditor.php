<?php

/*
riddle editor for 0.9.7 ext GER by anpera
based on the code from creature editor by mightye
*/

require_once 'common.php';
$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);

if(isset($_GET['on_off']))
{
	$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);

	$rid = (int)$_GET['rid'];

	// Switch
	$sql = 'UPDATE riddles SET enabled = IF(enabled=1,0,1) WHERE id='.$rid;
	db_query($sql);

	$str_back = '/mb Rätsel wurde umgeschaltet!';
	jslib_http_command($str_back);
	exit();
}

page_header('Rätseleditor');

$str_filename = basename(__FILE__);

if (access_control::is_superuser())
{
	grotto_nav();
	if ($_POST['save']<>'')
	{
		if ($_POST['id']!='')
		{
			$sql="UPDATE riddles SET riddle='".$_POST['riddle']."',answer='".$_POST['answer']."' WHERE id={$_POST['id']}";
			output(db_affected_rows().' '.(db_affected_rows()==1?'Eintrag':'Einträge').' geändert.');
		}
		else
		{
			$sql="INSERT INTO riddles (riddle,answer) VALUES ('".$_POST['riddle']."','".$_POST['answer']."')";
		}
		db_query($sql) or output("`\$".db_error(LINK)."`0`n`#$sql`0`n");
	}
	if ($_GET['op']=="del")
	{
		$sql = "DELETE FROM riddles WHERE id=".$_GET['id'];
		db_query($sql);
		if (db_affected_rows()>0)
		{
			output('Rätsel gelöscht`n`n');
		}
		else
		{
			output('Rätsel nicht gelöscht: '.db_error(LINK));
		}
		$_GET['op']='';
	}
	if ($_GET['op']=='')
	{
		$sql = "SELECT * FROM riddles ORDER BY riddle";
		$result = db_query($sql);
		addnav("Rätsel hinzufügen",$str_filename."?op=add");
		output("<table><tr><td>Ops</td><td width='50%'>Rätsel</td><td>Lösung</td></tr>",true);
		addnav("",$str_filename);
		$int_count = db_num_rows($result);
		for ($i=0;$i<$int_count;$i++)
		{
			$str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');
			$row = db_fetch_assoc($result);
			output("<tr class='".$str_trclass."'><td valign='top' nowrap='nowrap'> [");
			output(create_lnk('Edit',$str_filename.'?op=edit&id='.$row['id']));
			output('|'.create_lnk('Delete',$str_filename.'?op=del&id='.$row['id'],true,false,'Bist Du Dir sicher, dass du das Rätsel löschen willst?'),true);
			output('|'.jslib_int_switch($str_filename.'?on_off=1&rid='.$row['id'],$row['enabled']).'] ');
			output('</td><td>');

			output($row['riddle']);
			output("</td><td>",true);
			output($row['answer']);
			output("</td></tr>",true);
		}
		addpregnav('/'.$str_filename.'\?op=(edit|del)&id=\d+/');
		output("</table>",true);
	}
	else
	{
		if ($_GET['op']=="edit" || $_GET['op']=="add")
		{
			if ($_GET['op']=="edit")
			{
				$sql = "SELECT * FROM riddles WHERE id={$_GET['id']}";
				$result = db_query($sql);
				if (db_num_rows($result)<>1)
				{
					output("`4Fehler`0, dieses Rätsel wurde nicht gefunden!");
				}
				else
				{
					$row = db_fetch_assoc($result);
				}
			}
			output("<form action='su_riddleeditor.php' method='POST'>",true);
			output("<input name='id' value=\"".utf8_htmlentities($_GET['id'])."\" type='hidden'>",true);
			output("<table border='0' cellpadding='2' cellspacing='0'>",true);
			output("<tr><td>Rätsel:</td><td><textarea name='riddle' class='input' cols='50' rows='9'>".utf8_htmlentities(str_replace(array('`','³','²'),array('``','³³','²²'),$row['riddle']))."</textarea></td></tr>",true);
			output("<tr><td>Antwort: </td><td><input name='answer' maxlength='250' size='50' value=\"".utf8_htmlentities($row['answer'])."\"></td></tr>",true);
			output("<tr><td colspan='2'><input type='hidden' name='save' value='Save'><input type='submit' class='button' name='submit' value='Speichern'></td></tr>",true);
			output("</table>",true);
			output("</form>",true);
			addnav("","su_riddleeditor.php");
		}
		else
		{

		}
		addnav("Zurück zum Rätsel-Editor","su_riddleeditor.php");
	}
}
else
{
	output("Weil du versucht hast, die Götter zu betrügen, wurdest du niedergeschmettert!");
	addnews("`&".$session['user']['name']." wurde für den Versuch, die Götter zu betrügen, niedergeschmettert (hat versucht die Superuser-Seiten zu hacken).");
	$session['user']['hitpoints']=0;
}
page_footer();
?>