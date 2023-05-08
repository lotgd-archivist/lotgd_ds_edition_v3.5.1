<?php
// Ahnenforscher von Atrahor
// 05-07-2008
// Copyright 2008 by Bathóry

define('ES_KOSTEN_NODE',0);
define('GD_KOSTEN_EDIT',0);

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
page_header("Das Rathaus");

$op = $_GET['op'];
$id = (int)$_GET['id'];

$stammb = new CStammbaum($session['user']['acctid']);

if($op=='')
{
	output("`c`b`}D`Ie`tr A`yhnenfors`tch`Ie`}r`b`c`n`n
	`yIn `tei`In`}em kleinen Büro in den weiten Fluren des Rathauses sitzt der Ahnenforscher. Nachdem auf dein schüchternes Klopfen niemand reagiert, öffnest du die Tür und trittst ein. Sämtliche Wände des Zimmers sind mit Regalen voll gestellt, die unzählige in kostbares Leder gebundene Aktenmappen enthalten. Auf dem Boden stapeln sich lose Blätter, und lassen nur wenig Platz für die Füße frei. Hinter einem immensen Schreibtisch, der ebenfalls ganz und gar von unordentlich gestapelten Pergamentblättern bedeckt ist, entdeckst du ein kleines, weißhaariges Männchen, welches dich neugierig mustert. `n`y\"Na, wen haben wir denn da? Aaahh, Ihr müsst ".$session['user']['name']." sein.\" `n`}Ein wenig verwirrt nickst du. Woher kennt dieser Alte deinen Namen? `n`y\"Lasst mich raten, Ihr wollt Euren Stammbaum sehen?\" 
	
	");
	if(!$stammb->has_tree())
    {
        $stammb->make_tree();
    }

    output("`n`n`yHier habt ihr den Stammbaum Eurer edlen Familie.`n`n") ;
	($stammb->has_tree()) ? addnav('S?Stammbaum bearbeiten...','stammbaum.php?op=edit') : false;
	addnav('Z?Zurück zum Rathaus','dorfamt.php');
}
/*
else if($op=='new')
{
	output("`n`c`bEin Stammbaum zu registrieren.`n Hmmm... Das kostet Sie ".DP_KOSTEN_TREE." von diesen komischen Donationpoitns, aber nur weil Sie es sind!`b`c`n`n");

	addnav('J?Ja, das sind mir meine Ahnen wert!','stammbaum.php?op=newconfirm');
	addnav('N?Nein, das ist Wucher!','stammbaum.php');
}
else if($op=='newconfirm')
{
	if($stammb->do_payment(0,0,DP_KOSTEN_TREE))
	{
		if($stammb->make_tree())
		{
			output("`n`c`bDer Alte drückt dir nen verschmirten Zettel in die Hand und schaut dich erwartungsvoll an.`b`c`n");
			addnav('S?Stammbaum bearbeiten...','stammbaum.php?op=edit');
			addnav('D?Das war es für heute...','stammbaum.php');
		}
		else
		{
			output("`n`c`bUups, wende dich an die Götter! Ihre Ahnen sind verschwunden, so wie Ihre ".DP_KOSTEN_TREE." Donaitionpoints.`b`c`n");
			addnav('D?Das war es für heute...','stammbaum.php');
		}
	}
	else
	{
		output("`n`c`bSie haben keine ".DP_KOSTEN_TREE." Donaitionpoints, sagt der alte Mann ohne dich eines Blickes zu würdigen`b`c`n");
		addnav('D?Das war es für heute...','stammbaum.php');
	}
}
*/
else if($op=='edit')
{
	output("`n`yWi`ted`Ier`} nickst du zögerlich, und der Alte geht zielsicher auf eines der Regale zu, wobei er sich geschickt den Weg durch die Papierstapel bahnt, zieht nach einem kurzen Blick eine Mappe hervor und reicht sie dir. In der Mappe liegt sorgfältig gefaltet ein großer Pergamentbogen, der dir folgendes a`Inz`tei`ygt: `n`n");
	output($stammb->get_tree(2,0,false,true),true);
	addnav('D?Das war es für heute...','stammbaum.php');
}
else if($op=='editnew')
{
	output("`n`c`bEinen Eintrag hinzuzufügen.`n Hmmm... Das kostet Sie ".ES_KOSTEN_NODE." Edelsteine, aber nur weil Sie es sind!`b`c`n`n");

	addnav('J?Ja, das sind mir meine Ahnen wert!','stammbaum.php?op=editnewconfirm&id='.$id);
	addnav('N?Nein, das ist Wucher!','stammbaum.php?op=edit');
}
else if($op=='editnewconfirm')
{
	if($_GET['sop'] == 'save')
	{
		if($stammb->do_payment(0,ES_KOSTEN_NODE,0))
		{	
			$stammb->insert_node($_POST,$id,$session['user']['acctid']);
			redirect('stammbaum.php?op=edit');
			output("`n`c`bGesagt, getan und erstellt!`b`c`n");
			addnav('S?Stammbaum weiter bearbeiten...','stammbaum.php?op=edit');
			addnav('D?Das war es für heute...','stammbaum.php');
		}
		else
		{
			output("`n`c`bSie haben keine ".ES_KOSTEN_NODE." Edelsteine, sagt der alte Mann ohne dich eines Blickes zu würdigen`b`c`n");
			addnav('D?Das war es für heute...','stammbaum.php');
		}
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
		$head['ehepartner_sex'] = 'Geschlecht,select,0,----,1,Männlich,2,Weiblich';
		$head['ep_gtag'] = 'Geburtstag,text';
		$head['ep_stag'] = 'ggf. Todestag,text';;
		
		$val['name'] = '';
		$val['gtag'] = '';
		$val['stag'] = ''; 
		$val['status'] = 1;
		$val['bast_vater'] = ''; 
		$val['bast_mutter'] = '';
		$val['ehepartner'] = '';
		$val['ehepartner_sex'] = '';
		$val['ep_gtag'] = '';
		$val['ep_stag'] = '';

		
		// Formular anzeigen
		$str_lnk = 'stammbaum.php?op=editnewconfirm&sop=save&id='.$id;
		output('`n<form action="'.$str_lnk.'" method="POST" enctype="multipart/form-data">');
		showform($head,$val,false,'Speichern',6);
		output('</form>');
		// END Formular anzeigen
		
		addnav('',$str_lnk);
		addnav('D?Das war es für heute...','stammbaum.php');
	}
}
else if($op=='editnewparent')
{
	output("`n`c`bEinen Eintrag hinzuzufügen.`n Hmmm... Das kostet Sie ".ES_KOSTEN_NODE." Edelsteine, aber nur weil Sie es sind!`b`c`n`n");

	addnav('J?Ja, das sind mir meine Ahnen wert!','stammbaum.php?op=editnewconfirm&id='.$id);
	addnav('N?Nein, das ist Wucher!','stammbaum.php?op=edit');
}
else if($op=='editnewparentconfirm')
{
	if($_GET['sop'] == 'save')
	{
		if($stammb->do_payment(0,ES_KOSTEN_NODE,0))
		{	
			$stammb->insert_parent($_POST,$id,$session['user']['acctid']);
			redirect('stammbaum.php?op=edit');
			output("`n`c`bGesagt, getan und erstellt!`b`c`n");
			addnav('S?Stammbaum weiter bearbeiten...','stammbaum.php?op=edit');
			addnav('D?Das war es für heute...','stammbaum.php');
		}
		else
		{
			output("`n`c`bSie haben keine ".ES_KOSTEN_NODE." Edelsteine, sagt der alte Mann ohne dich eines Blickes zu würdigen`b`c`n");
			addnav('D?Das war es für heute...','stammbaum.php');
		}
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
		$head['ehepartner_sex'] = 'Geschlecht,select,0,----,1,Männlich,2,Weiblich';
		$head['ep_gtag'] = 'Geburtstag,text';
		$head['ep_stag'] = 'ggf. Todestag,text';
		
		$val['name'] = '';
		$val['gtag'] = '';
		$val['stag'] = ''; 
		$val['status'] = 1;
		$val['bast_vater'] = ''; 
		$val['bast_mutter'] = '';
		$val['ehepartner'] = '';
		$val['ehepartner_sex'] = '';
		$val['ep_gtag'] = '';
		$val['ep_stag'] = '';

		
		// Formular anzeigen
		$str_lnk = 'stammbaum.php?op=editnewparentconfirm&sop=save&id='.$id;
		output('`n<form action="'.$str_lnk.'" method="POST" enctype="multipart/form-data">');
		showform($head,$val,false,'Speichern',6);
		output('</form>');
		// END Formular anzeigen
		
		addnav('',$str_lnk);
		addnav('D?Das war es für heute...','stammbaum.php');
	}
}
else if($op=='editold')
{
	output("`n`c`bEin Eintrag zu ändern.`n Hmmm... Das kostet Sie ".GD_KOSTEN_EDIT." Goldmünzen, aber nur weil Sie es sind!`b`c`n`n");

	addnav('J?Ja, das sind mir meine Ahnen wert!','stammbaum.php?op=editoldconfirm&id='.$id);
	addnav('N?Nein, das ist Wucher!','stammbaum.php?op=edit');
}
else if($op=='editoldconfirm')
{
	if($_GET['sop'] == 'save')
	{
		if($stammb->do_payment(GD_KOSTEN_EDIT,0,0))
		{	
			$stammb->update_node($_POST,$id);
			redirect('stammbaum.php?op=edit');
			output("`n`c`bGesagt, getan und geändert!`b`c`n");
			addnav('S?Stammbaum weiter bearbeiten...','stammbaum.php?op=edit');
			addnav('D?Das war es für heute...','stammbaum.php');
		}
		else
		{
			output("`n`c`bSie haben keine ".GD_KOSTEN_EDIT." Goldmünzen, sagt der alte Mann ohne dich eines Blickes zu würdigen`b`c`n");
			addnav('D?Das war es für heute...','stammbaum.php');
		}
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
		$head['ehepartner_sex'] = 'Geschlecht,select,0,----,1,Männlich,2,Weiblich';
		$head['ep_gtag'] = 'Geburtstag,text';
		$head['ep_stag'] = 'ggf. Todestag,text';;
		
		$val = $stammb->get_nodedata($id);
		
		// Formular anzeigen
		$str_lnk = 'stammbaum.php?op=editoldconfirm&sop=save&id='.$id;
		output('`n<form action="'.$str_lnk.'" method="POST" enctype="multipart/form-data">');
		showform($head,$val,false,'Speichern',6);
		output('</form>');
		// END Formular anzeigen
		
		addnav('',$str_lnk);
		addnav('D?Das war es für heute...','stammbaum.php');
	}
}
else if($op=='editdel')
{
	output("`n`c`bEin Eintrag zu löschen.`n Hmmm... Ok, aber nur weil Sie es sind!`n Die Nachkommen werden auch alle gelöscht`b`c`n`n");

	addnav('M?Mit Nachkommen löschen!','stammbaum.php?op=editdelconfirm&sop=mit&id='.$id);
	//addnav('O?Ohne Nachkommen!!','stammbaum.php?op=editdelconfirm&sop=ohne&id='.$id);
	addnav('N?Nein, das ist Wucher!','stammbaum.php?op=edit');
}
else if($op=='editdelconfirm')
{

	($_GET['sop'] == 'ohne') ? $delete_subs = false : $delete_subs = true ;
	
	$stammb->delete_node($id,$delete_subs);
	
	redirect('stammbaum.php?op=edit');
	
	/*output("`n`c`bGesagt, getan und gelöscht!`b`c`n");
	addnav('S?Stammbaum weiter bearbeiten...','stammbaum.php?op=edit');
	addnav('D?Das war es für heute...','stammbaum.php');*/

}

unset($stammb);

page_footer();
?>