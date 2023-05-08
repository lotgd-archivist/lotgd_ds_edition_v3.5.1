<?php
/**
* su_mazeedit.php: Editor für Schlosskarten
* @author Alucard
* @version DS-E V/2
*/


$str_filename = basename(__FILE__);
require_once('common.php');

//checkday();
page_header("Maze Editor");

addnav('Zurück');
grotto_nav();
addnav('Aktionen');

output("`c`b`&Maze Editor`0`b`c");

if($session['message'] != '') {
	output('`n`b'.$session['message'].'`b`n`n');
	$session['message'] = '';
}

switch($_GET['op']) {
			
	// Kartenansicht
	case 'editmap':
		
		addnav("E?Editmode beenden",$str_filename);
				
		$mazeid = (int)$_REQUEST['mazeid'];
				
		// Wenn bestimmtes Maze editiert werden soll
		if($mazeid > 0) {
			
			$sql = 'SELECT * FROM mazes WHERE mazeid='.$mazeid;
			$maze = db_fetch_assoc(db_query($sql));
		}

		
		$data = array(	'mazetitle'=> empty($maze['mazetitle']) ? "noname" : $maze['mazetitle'],
						'mazeauthor'=> empty($maze['mazeauthor']) ? $session['user']['login'] : $maze['mazeauthor'],
						'mazechance'=> !isset($maze['mazechance']) ? 1 : $maze['mazechance'],
						'mazegold'=> empty($maze['mazegold']) ? 5750 : $maze['mazegold'],
						'mazegems'=> empty($maze['mazegems']) ? 4 : $maze['mazegems'],
						'mazeturns'=>empty($maze['mazeturns']) ? 20 : $maze['mazeturns'],
						'maze'=>$maze['maze']);
		
		$form = array('mazetitle'=>'Titel',
						'mazeauthor'=>'Autor',
						'mazechance'=>'Aktiv,bool',
						'mazegold'=>'Max. Gold für dieses Schloss',
						'mazegems'=>'Max. Gems für dieses Schloss',
						'mazeturns'=>'Max. Züge für max. Ertrag',
						'maze'=>'Mapcode');
		
		// Kartenansicht
		$out = "
				<style>
				@import url('templates/mazeedit.css');				
				</style>
				<script type=\"text/javascript\" src=\"templates/mazeedit.js\"></script>
				<table>
				<tr>
					<td>
						<table border=\"0\" cellpadding=\"0\" colspan=\"0\" rowspan=\"0\" cellspacing=\"0\">";
		
		
		
		for($i=12;$i>-1;$i--){
			$out .= "<tr>";
			for($k=0;$k<11;$k++){
				$out .= "<td class=\"mazefieldok\" id=\"mazefield".(($i*11)+$k)."\" onClick=\"javascript:setField(this, ".(($i*11)+$k).")\"><img style=\"display: block;\" src=\"./images/castle/xmaze25.gif\" width=\"25\" height=\"25\"><td>";
			}			
			$out .= "</tr>\n";
		}
		
		$out .= "</table>
					
					</td>
					<td>
						<table border=\"0\" cellpadding=\"0\" colspan=\"0\" rowspan=\"0\" cellspacing=\"0\">
						<tr><td colspan=\"3\" align=\"center\"><span id=\"startsestime\"></span></td></tr>
						<tr><td colspan=\"3\" align=\"center\"><b>Teile</b></td></tr>
						<tr>
							<td id=\"inittile\" class=\"currenttile\" onClick=\"javascript:setCurrent(this, 'i')\"><img style=\"display: block;\" src=\"./images/castle/imaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'c')\"><img style=\"display: block;\" src=\"./images/castle/cmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'h')\"><img style=\"display: block;\" src=\"./images/castle/hmaze25.gif\" width=\"25\" height=\"25\"></td>
					
						</tr>
						<tr>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'f')\"><img style=\"display: block;\" src=\"./images/castle/fmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'a')\"><img style=\"display: block;\" src=\"./images/castle/amaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'e')\"><img style=\"display: block;\" src=\"./images/castle/emaze25.gif\" width=\"25\" height=\"25\"></td>
						</tr>
						<tr>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'j')\"><img style=\"display: block;\" src=\"./images/castle/jmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'b')\"><img style=\"display: block;\" src=\"./images/castle/bmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'k')\"><img style=\"display: block;\" src=\"./images/castle/kmaze25.gif\" width=\"25\" height=\"25\"></td>
						</tr>
						<tr>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'd')\"><img style=\"display: block;\" src=\"./images/castle/dmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'g')\"><img style=\"display: block;\" src=\"./images/castle/gmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'l')\"><img style=\"display: block;\" src=\"./images/castle/lmaze25.gif\" width=\"25\" height=\"25\"></td>
						</tr>
						<tr>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'n')\"><img style=\"display: block;\" src=\"./images/castle/nmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'm')\"><img style=\"display: block;\" src=\"./images/castle/mmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'o')\"><img style=\"display: block;\" src=\"./images/castle/omaze25.gif\" width=\"25\" height=\"25\"></td>
						</tr>
						<tr>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'p')\"><img style=\"display: block;\" src=\"./images/castle/pmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'q')\"><img style=\"display: block;\" src=\"./images/castle/qmaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'r')\"><img style=\"display: block;\" src=\"./images/castle/rmaze25.gif\" width=\"25\" height=\"25\"></td>
						</tr>
						<tr>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 's')\"><img style=\"display: block;\" src=\"./images/castle/smaze25.gif\" width=\"25\" height=\"25\"></td>
							<td class=\"notile\" onClick=\"javascript:setCurrent(this, 'z')\"><img style=\"display: block;\" src=\"./images/castle/zmaze25.gif\" width=\"25\" height=\"25\"></td>
						</tr>
						<tr>
							<td colspan=\"3\" align=\"center\"><input onclick=\"javascript:debugmap(window.document.mazevalues.maze.value)\" type=\"button\" value=\"debug\"></td>
						</tr>
						<tr>
							<td colspan=\"3\" align=\"center\"><b>Grid: </b><input onclick=\"javascript:switchgrid(this)\" type=\"button\" value=\"off\"></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=\"2\">";
		$link = $str_filename.'?op=savemap';
						
		$out .=	"<form name=\"mazevalues\" method=\"POST\" action=\"".$link."\">";
		addnav("",$link);
		$out .=	"<input type=\"hidden\" value=\"".$mazeid."\" name=\"mazeid\">";				
		output($out,true);				
		showform($form,$data);
						
		$out =	"		</form>
					</td>
				</tr>
				".'<script type="text/javascript">
					Init();
					sesswarn();
					</script>
				'."
				</table>
				<div class=\"debugview\" id=\"dbgwnd\">
					<textarea class=\"dbglog\" id=\"debuglog\"></textarea><br>
					<input onclick=\"javascript:closedebug()\" type=\"button\" value=\"close\">
				</div>";
		
		
		output($out,true);
					
		break;
	
	
	// Karte löschen
	case 'delmap':
		
		$mazeid = (int)$_GET['mazeid'];
		
		$sql = 'DELETE FROM mazes WHERE mazeid='.$mazeid;
		db_query($sql);
		
		if(!db_affected_rows()) {
			$session['message'] = '`$Fehler bei Löschen!';
			
		}
		else {
			$session['message'] = '`@Erfolgreich gelöscht!';

		}
		
		redirect($str_filename.'');
		
		break;
	
	// Speichern	
	case 'savemap':
		
		$mazeid = (int)$_REQUEST['mazeid'];
						
		$sql = ($mazeid ? 'UPDATE mazes SET ' : 'INSERT INTO mazes SET ');
		
		$sql .= ' mazetitle="'.$_POST['mazetitle'].'",
		            mazechance="'.$_POST['mazechance'].'",
					mazeauthor="'.$_POST['mazeauthor'].'",
					mazegold="'.$_POST['mazegold'].'",
					mazegems="'.$_POST['mazegems'].'",
					mazeturns="'.$_POST['mazeturns'].'",
					maze="'.$_POST['maze'].'"';
					
		$sql .= ($mazeid ? ' WHERE mazeid='.$mazeid : '');
		
		if(db_query($sql)) {
			$session['message'] = '`@Schloß erfolgreich gespeichert!`0';
		}
		else {
			$session['message'] = '`$Fehler beim Speichern!`0';
		}
		
		redirect($str_filename.'');
						
		break;
	
	
	// Standardansicht, Auswahl
	default:
		
		$out = '`c<table cellspacing="2" cellpadding="2"><tr class="trhead">
					<td>`bID`b</td>		
					<td>`bName`b</td>
					<td>`bAutor`b</td>
					<td>`bStatus`b</td>
					<td>`bAktionen`b</td>
				</tr>';
		
		addnav('NEU',$str_filename.'?op=editmap&mazeid=0');
		
		$sql = 'SELECT * FROM mazes ORDER BY mazeid ASC';
		$res = db_query($sql);
				
		while($c = db_fetch_assoc($res)) {
			
			$style = ($style == 'trlight' ? 'trdark' : 'trlight');
			$editlink_map = $str_filename.'?op=editmap&mazeid='.$c['mazeid'];
			addnav('',$editlink_map);
			$dellink = $str_filename.'?op=delmap&mazeid='.$c['mazeid'];
			addnav('',$dellink);
			
			$out .= '<tr class="'.$style.'">
						<td>'.$c['mazeid'].'</td>
						<td>'.$c['mazetitle'].'`&</td>
						<td>'.$c['mazeauthor'].'`&</td>
						<td>'.($c['mazechance'] > 0 ? '`@Aktiv`0':'`$Inaktiv`0').'`&</td>
						<td>
							[ <a href="'.$editlink_map.'">Edit Map</a> ] 
							[ `$<a href="'.$dellink.'" onClick="return confirm(\'Willst du dieses Schloß wirklich löschen?\');">Del</a>`& ]
						</td>
					</tr>';
			
		}	
		
		$out .= '</table>`c';
		
		output($out,true);
		
		break;
	
}

page_footer();
