<?php

// Tool, um dem User Level oder Anwendungen in besonderen Fähigkeiten zu geben
// by Ravenclaw (Tyndal)

require_once "common.php";
require_once(LIB_PATH.'dg_funcs.lib.php');
$access_control->su_check(access_control::SU_RIGHT_EDITORUSER,true);

page_header("User-Editor : Besondere Fähigkeiten");
grotto_nav();
addnav("User-Editor");
addnav("User-Editor","user.php?op=edit&userid=".$_GET['userid']);
addnav("Zusatzinfos","user.php?op=edit2&userid=".$_GET['userid']);

switch($_GET['op'])
{
	case "edit":
		if ($_GET['returnpetition']) $retpet="&".$_GET['returnpetition'];
		$result=db_query("SELECT * FROM specialty");
		$user=db_fetch_assoc(db_query("SELECT specialtyuses FROM accounts WHERE acctid=".$_GET['userid']));
		$spuses=utf8_unserialize($user['specialtyuses']);
		addnav("","user_special.php?op=save&userid=".$_GET['userid'].$retpet);
		$form="`c<form action='user_special.php?op=save&userid=".$_GET['userid'].$retpet."' method='POST'>";
		$form.="<table cellspacing=0 border=1 width='75%'><tr><td>";
		while ($row=db_fetch_assoc($result))
		{
			$i++;
			$usename=$row['usename'];
			$form.="<table width='50%' border=0>";
			$form.="<tr align='center' bgcolor='#000064'><td colspan=2>".$row['specname']."</td></tr>";
			$form.="<tr><td>Level</td><td><input name='".$usename."' value='".$spuses[$usename]."'></td></tr>";
			$form.="<tr><td>Anwendungen</td><td><input name='".$usename."uses' value='".$spuses[$usename.'uses']."'></td></tr>";
			$form.="</table>";
			if ($i%2==1) $form.="</td><td>";
			else $form.="</td></tr><tr><td>";
		}
		if ($i%2==0) $form=mb_substr($form,0,mb_strlen($form)-4);
		else $form.="</td>";
		$form.="</tr></table>`n<input type='submit' class='button' value='Speichern'></form>`c";
		output($form);
		break;
	case "save":
		foreach ($_POST as $key => $val)
		{
			if ($val=="")
			{
				$val="0";
			}
			$specuses[$key]=(int)$val;
		}
		$specuses=utf8_serialize($specuses);
		user_update(
			array
			(
				'specialtyuses'=>$specuses
			),
			$_GET['userid']
		);
		
		//Selber editieren muss gesondert behandelt werden, sonst überschreibt man seine eigenen Einstellungen
		if($session['user']['acctid'] == $_GET['userid'])
		{
			$session['user']['specialtyuses'] = $specuses;
		}
		redirect(basename(__FILE__.'?op=edit&userid='.$_GET['userid']));
		break;
	default:
}

page_footer();
?>