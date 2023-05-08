<?php
/*
* @author Báthory
*/

require_once('inc/global.php');

function editorprocess($r,$filename,$i)
{
	return '<tr class="'.($i%2?'trdark':'trlight').'" style="'.( ($r['activ']) ? '' : 'background:#777;').'">
				<td><b>'.$r['id'].'</b></td>
				<td><b>'.$r['name'].'</b></td>
				<td><b>'.$r['link'].'</b></td>
				<td><b>'.($r['activ'] ? '<a href="'.$filename.'op='.$_GET['op'].'&do=deac&id='.$r['id'].'">Deak</a>' : '<a href="'.$filename.'op='.$_GET['op'].'&do=ac&id='.$r['id'].'">Akt</a>').'</b></td>
				<td><b><a href="'.$filename.'op='.$_GET['op'].'&sop=edit&id='.$r['id'].'">Edit</a></b></td>
				</tr>';	
}

$name = 'Quest Orte';
$table = ' quest_orte ';
$orderby = " ORDER BY name";

$head[] = 'Allgemein,title';
$head[] = 'Name,divider';
$head['name'] = 'Name,text,255';
$head[] = 'Link,divider';
$head['link'] = 'Link,text,255';

$header = '<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%"><tr class="trhead">
				<th>Id</th>
				<th>Name</th>
				<th>Link</th>
				<th>Aktiv</th>
				<th>Edit</th>
			</tr>';

require_once('inc/editor.php');

?>