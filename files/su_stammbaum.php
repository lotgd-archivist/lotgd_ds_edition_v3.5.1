<?php
// Stammbaum admin
// 05-07-2008
// Copyright 2008 by Bathóry

$str_filename = basename(__FILE__);
require_once('common.php');

$access_control->su_check(access_control::SU_RIGHT_BIOS,true);

page_header('Stammbaum-Admin');

$op = $_GET['op'];
$id = (int)$_GET['id'];
$acctid = (int)$_GET['userid'];

$stammb = new CStammbaum($acctid);

addnav('Zurück');
grotto_nav();
addnav('Aktionen');
addnav('Aktualisieren',$str_filename.'?userid='.$acctid);
addnav('Zurück zum User-Editor','user.php?op=edit&userid='.$acctid);
// END Grundnavi erstellen


output('`n`c`bStammbaum von UserId:'.$acctid.' bearbeiten.`b`c`n');

if($op=='')
{
	output($stammb->get_tree_as_admin(true,$acctid),true);
}
else if($op=='editnewconfirm')
{
	if($_GET['sop'] == 'save')
	{	
			$stammb->insert_node($_POST,$id,$acctid);
			redirect('su_stammbaum.php?userid='.$acctid);
	}
	else
	{
		$head['eintrag'] = 'Eintrag,title';
		$head['name'] = 'Name,text,200';
		$head['sex'] = 'Geschlecht,select,1,Männlich,2,Weiblich';
		$head['gtag'] = 'Geburtstag,text';
		$head['stag'] = 'ggf. Todestag,text'; 
		$head['bast_t'] = 'Andere Eltern,title';
		$head['bast_vater'] = 'Name des andere Vaters,text,200';
		$head['bast_mutter'] = 'Name der anderen Mutter,text,200';
		$head['ehepartner_t'] = 'Ehepartner,title';
		$head['ehepartner'] = 'Name,text,200';
		$head['ep_gtag'] = 'Geburtstag,text';
		$head['ep_stag'] = 'ggf. Todestag,text';;
		
		$val['name'] = '';
		$val['gtag'] = '';
		$val['stag'] = ''; 
		$val['status'] = 1;
		$val['bast_vater'] = ''; 
		$val['bast_mutter'] = '';
		$val['ehepartner'] = '';
		$val['ep_gtag'] = '';
		$val['ep_stag'] = '';

		
		// Formular anzeigen
		$str_lnk = 'su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&sop=save&id='.$id;
		output('`n<form action="'.$str_lnk.'" method="POST" enctype="multipart/form-data">');
		showform($head,$val,false,'Speichern',6);
		output('</form>');
		// END Formular anzeigen
		
		addnav('',$str_lnk);
		addnav('D?Das war es für heute...','su_stammbaum.php?userid='.$acctid);
	}
}
else if($op=='editnewparentconfirm')
{
	if($_GET['sop'] == 'save')
	{
			$stammb->insert_parent($_POST,$id,$acctid);
			redirect('su_stammbaum.php?userid='.$acctid);
	}
	else
	{
		$head['eintrag'] = 'Eintrag,title';
		$head['name'] = 'Name,text,200';
		$head['sex'] = 'Geschlecht,select,1,Männlich,2,Weiblich';
		$head['gtag'] = 'Geburtstag,text';
		$head['stag'] = 'ggf. Todestag,text'; 
		$head['bast_t'] = 'Andere Eltern,title';
		$head['bast_vater'] = 'Name des andere Vaters,text,200';
		$head['bast_mutter'] = 'Name der anderen Mutter,text,200';
		$head['ehepartner_t'] = 'Ehepartner,title';
		$head['ehepartner'] = 'Name,text,200';
		$head['ep_gtag'] = 'Geburtstag,text';
		$head['ep_stag'] = 'ggf. Todestag,text';
		
		$val['name'] = '';
		$val['gtag'] = '';
		$val['stag'] = ''; 
		$val['status'] = 1;
		$val['bast_vater'] = ''; 
		$val['bast_mutter'] = '';
		$val['ehepartner'] = '';
		$val['ep_gtag'] = '';
		$val['ep_stag'] = '';

		
		// Formular anzeigen
		$str_lnk = 'su_stammbaum.php?userid='.$acctid.'&op=editnewparentconfirm&sop=save&id='.$id;
		output('`n<form action="'.$str_lnk.'" method="POST" enctype="multipart/form-data">');
		showform($head,$val,false,'Speichern',6);
		output('</form>');
		// END Formular anzeigen
		
		addnav('',$str_lnk);
		addnav('D?Das war es für heute...','su_stammbaum.php?userid='.$acctid);
	}
}
else if($op=='editoldconfirm')
{
	if($_GET['sop'] == 'save')
	{
			$stammb->update_node($_POST,$id);
			redirect('su_stammbaum.php?userid='.$acctid);		
	}
	else
	{
		$head['eintrag'] = 'Eintrag,title';
		$head['name'] = 'Name,text,200';
		$head['sex'] = 'Geschlecht,select,1,Männlich,2,Weiblich';
		$head['gtag'] = 'Geburtstag,text';
		$head['stag'] = 'ggf. Todestag,text'; 
		$head['bast_t'] = 'Andere Eltern,title';
		$head['bast_vater'] = 'Name des andere Vaters,text,200';
		$head['bast_mutter'] = 'Name der anderen Mutter,text,200';
		$head['ehepartner_t'] = 'Ehepartner,title';
		$head['ehepartner'] = 'Name,text,200';
		$head['ep_gtag'] = 'Geburtstag,text';
		$head['ep_stag'] = 'ggf. Todestag,text';;
		
		$val = $stammb->get_nodedata($id);
		
		// Formular anzeigen
		$str_lnk = 'su_stammbaum.php?userid='.$acctid.'&op=editoldconfirm&sop=save&id='.$id;
		output('`n<form action="'.$str_lnk.'" method="POST" enctype="multipart/form-data">');
		showform($head,$val,false,'Speichern',6);
		output('</form>');
		// END Formular anzeigen
		
		addnav('',$str_lnk);
		addnav('D?Das war es für heute...','su_stammbaum.php?userid='.$acctid);
	}
}
else if($op=='editdelconfirm')
{
		$stammb->delete_node($id,true);
		redirect('su_stammbaum.php?userid='.$acctid);
}

unset($stammb);


page_footer();	
?>