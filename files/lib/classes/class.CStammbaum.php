<?php

//Ctree von Alucard
class CStamNode extends CNode {
	
	protected $str_test = '';
	public function __construct( $str ){
		$this->str_test = $str;
	}
	
       //draw funktion überschreiben
	public function draw(){ 
		return '<div style="width: 160px; border-width: 1px; border-color: #CCCCCC; border-style: solid; padding: 3px; margin-left:5px; margin-right:5px;">'.$this->str_test.'</div>		';
	}
}



// 05-07-2008 
// Bathóry
// SQL-Querys mostly from: http://dev.mysql.com/tech-resources/articles/hierarchical-data.html

class CStammbaum
{

	/**
	 * User-ID dem der Stammbaum gehört
	 *
	 * @var int
	 */
	private $actual_user;
	
	/**
	 * Name der Tabelle
	 *
	 * @var string
	 */
	private $table_name;
	
	/**
	 * Constructor
	 */
	function __construct($userid=0,$table='stammbaum')
	{
		$this->actual_user = (int) $userid;
		$this->table_name = $table;
	}
	
	/**
	 * Liefert den kompletten Stammbaum
	 *@param $modus : 0 = als html liste; 1 = als array; 2 = als richtigen html stammbaum; 3 = als mysql query;
	 *@param $subtree_id : 0 = ganzer baum, > 0 = id des parent des subbaums.
	 *@param $sons_only : nur die Söhne von einem parent / benötigt subtree_id > 0
	 *@param $editable: ob der baum bearbeitbar sein soll
	 */
	public function get_tree($modus=2,$subtree_id=0,$sons_only=false,$editable=false)
	{				
		$sql = " SELECT node.*, (COUNT(parent.id) ";
		($subtree_id > 0) ? $sql .= " - (sub_tree.depth + 1)) " : $sql .= " - 1) ";
		$sql .= " AS depth FROM ".$this->table_name." AS node, ".$this->table_name." AS parent ";
		($subtree_id > 0) ? $sql .= " , ".$this->table_name." AS sub_parent, 
			( 
				SELECT node.*, (COUNT(parent.id) - 1) AS depth FROM ".$this->table_name." AS node, ".$this->table_name." AS parent 
				WHERE node.acctid = '".$this->actual_user."' AND parent.acctid = '".$this->actual_user."' AND node.lft BETWEEN parent.lft AND parent.rgt AND node.id = '".$subtree_id."' 
				GROUP BY node.id 
				ORDER BY node.lft 
			) 
			AS sub_tree" : $sql .= " ";
		$sql .= " WHERE node.acctid = '".$this->actual_user."' AND parent.acctid = '".$this->actual_user."' AND node.lft BETWEEN parent.lft AND parent.rgt ";
		($subtree_id > 0) ? $sql .= " AND sub_parent.acctid = '".$this->actual_user."' AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt AND sub_parent.id = sub_tree.id " : $sql .= " ";
		$sql .= " GROUP BY node.id ";
		(($subtree_id > 0) and $sons_only) ? $sql .= "HAVING depth = 1" : $sql .= " ";
		$sql .= " ORDER BY node.lft; ";	
		
		switch($modus)
		{	
			default:
			case 0 : $return = $this->paint_tree_as_list(db_query($sql));
					 break;
			case 1 : $return = $this->paint_tree_as_array(db_query($sql));
					 break;
			case 2 : $return = $this->paint_tree(db_query($sql),$editable);
					 break;
			case 3 : $return = db_query($sql);
					 break;
		}
		
		return $return;
	}
	
	/**
	 * Liefert den Pfad zu einem Knoten als array
	 *@param $node_id : id des knoten
	 */
	public function get_path($node_id)
	{
		$sql = "SELECT parent.id
				FROM ".$this->table_name." AS node,
				".$this->table_name." AS parent
				WHERE parent.acctid = '".$this->actual_user."' AND node.lft BETWEEN parent.lft AND parent.rgt
				AND node.id = '".$node_id."'
				ORDER BY parent.lft; ";
				
		return $this->paint_tree_as_array(db_query($sql));
	}
	
	/**
	 * Liefert alle Blätter als array
	 */
	public function get_leaf_nodes()
	{
		$sql = "SELECT name
				FROM ".$this->table_name."
				WHERE acctid = '".$this->actual_user."' AND rgt = lft + 1;";
				
		return $this->paint_tree_as_array(db_query($sql));
	}
	
	/**
	 * Insert Node
	 *@param $data : array mit dem daten
	 *@param $id : id des parent
     *@param $acctid : id des spielers
	 */
	public function insert_node($data,$id,$acctid)
	{
		if($this->has_sons($id))
		{
			$pid = $this->last_son_id($id);
			
			$sql = "LOCK TABLE ".$this->table_name." WRITE";
			db_query($sql);
					
			$sql = "SELECT rgt FROM ".$this->table_name." WHERE id = '".$pid."'";
			$row = db_fetch_assoc(db_query($sql));
			$myRight = $row['rgt'];
			
			$sql = "UPDATE ".$this->table_name." SET rgt = rgt + 2 WHERE acctid = '".$this->actual_user."' AND rgt > ".$myRight;
			db_query($sql);	
			$sql = "UPDATE ".$this->table_name." SET lft = lft + 2 WHERE acctid = '".$this->actual_user."' AND lft > ".$myRight;
			db_query($sql);	
					
			$sql = "INSERT INTO ".$this->table_name."(acctid, name, gtag, stag, status, sex, bast_vater, bast_mutter, ehepartner,ehepartner_sex, ep_gtag, ep_stag, lft, rgt) 
					VALUES (
					'".$acctid."',
					'".utf8_htmlspecialchars($data['name'])."',
					'".utf8_htmlspecialchars($data['gtag'])."',
					'".utf8_htmlspecialchars($data['stag'])."',
					'1',
					'".utf8_htmlspecialchars($data['sex'])."',
					'".utf8_htmlspecialchars($data['bast_vater'])."',
					'".utf8_htmlspecialchars($data['bast_mutter'])."',
					'".utf8_htmlspecialchars($data['ehepartner'])."',
					'".utf8_htmlspecialchars($data['ehepartner_sex'])."',
					'".utf8_htmlspecialchars($data['ep_gtag'])."',
					'".utf8_htmlspecialchars($data['ep_stag'])."', 
					".$myRight." + 1, 
					".$myRight." + 2
					)";
			db_query($sql);	
					
			$sql = "UNLOCK TABLES";
			db_query($sql);	
		}
		else
		{
			$sql = "LOCK TABLE ".$this->table_name." WRITE";
			db_query($sql);
					
			$sql = "SELECT lft FROM ".$this->table_name." WHERE id = '".$id."'";
			$row = db_fetch_assoc(db_query($sql));
			$myLeft = $row['lft'];
			
			$sql = "UPDATE ".$this->table_name." SET rgt = rgt + 2 WHERE acctid = '".$this->actual_user."' AND rgt > ".$myLeft;
			db_query($sql);	
			$sql = "UPDATE ".$this->table_name." SET lft = lft + 2 WHERE acctid = '".$this->actual_user."' AND lft > ".$myLeft;
			db_query($sql);	
					
			$sql = "INSERT INTO ".$this->table_name."(acctid, name, gtag, stag, status, sex, bast_vater, bast_mutter, ehepartner, ehepartner_sex, ep_gtag, ep_stag, lft, rgt) 
					VALUES (
					'".$acctid."',
					'".utf8_htmlspecialchars($data['name'])."',
					'".utf8_htmlspecialchars($data['gtag'])."',
					'".utf8_htmlspecialchars($data['stag'])."',
					'1',
					'".utf8_htmlspecialchars($data['sex'])."',
					'".utf8_htmlspecialchars($data['bast_vater'])."',
					'".utf8_htmlspecialchars($data['bast_mutter'])."',
					'".utf8_htmlspecialchars($data['ehepartner'])."',
					'".utf8_htmlspecialchars($data['ehepartner_sex'])."',
					'".utf8_htmlspecialchars($data['ep_gtag'])."',
					'".utf8_htmlspecialchars($data['ep_stag'])."', 
					".$myLeft." + 1, 
					".$myLeft." + 2
					)";
			db_query($sql);	
					
			$sql = "UNLOCK TABLES";
			db_query($sql);	
		}
				
				
	}
	
	/**
	 * Insert parent
	 *@param $data : array mit dem daten
	 *@param $id : id des neuen sohns
     *@param $acctid : id des spielers
	 */
	public function insert_parent($data,$id,$acctid)
	{
		$parentid = $this->parent_id($id);
		
		$sql = "LOCK TABLE ".$this->table_name." WRITE";
		db_query($sql);
				
		$sql = "SELECT rgt,lft FROM ".$this->table_name." WHERE id = '".$parentid."'";
		$row = db_fetch_assoc(db_query($sql));
		$myRight = $row['rgt'];
		$myLeft = $row['lft'];
		
		
	
		$sql = "UPDATE ".$this->table_name." SET rgt = rgt + 2 WHERE acctid = '".$this->actual_user."' AND rgt > ".$myRight;
		db_query($sql);	
		$sql = "UPDATE ".$this->table_name." SET lft = lft + 2 WHERE acctid = '".$this->actual_user."' AND lft > ".$myRight;
		db_query($sql);	
		
		
		$sql = "UPDATE ".$this->table_name." SET rgt = rgt + 1 WHERE acctid = '".$this->actual_user."' AND rgt BETWEEN ".($myLeft+1)." AND ".($myRight-1)."";
		db_query($sql);	
		$sql = "UPDATE ".$this->table_name." SET lft = lft + 1 WHERE acctid = '".$this->actual_user."' AND lft BETWEEN ".($myLeft+1)." AND ".($myRight-1)."";
		db_query($sql);	
	
		
		$sql = "UPDATE ".$this->table_name." SET rgt = rgt + 2 WHERE acctid = '".$this->actual_user."' AND id = '".$parentid."'";
		db_query($sql);
			
						
		$sql = "INSERT INTO ".$this->table_name."(acctid, name, gtag, stag, status, sex, bast_vater, bast_mutter, ehepartner,ehepartner_sex, ep_gtag, ep_stag, lft, rgt) 
				VALUES (
				'".$acctid."',
				'".utf8_htmlspecialchars($data['name'])."',
				'".utf8_htmlspecialchars($data['gtag'])."',
				'".utf8_htmlspecialchars($data['stag'])."',
				'1',
				'".utf8_htmlspecialchars($data['sex'])."',
				'".utf8_htmlspecialchars($data['bast_vater'])."',
				'".utf8_htmlspecialchars($data['bast_mutter'])."',
				'".utf8_htmlspecialchars($data['ehepartner'])."',
				'".utf8_htmlspecialchars($data['ehepartner_sex'])."',
				'".utf8_htmlspecialchars($data['ep_gtag'])."',
				'".utf8_htmlspecialchars($data['ep_stag'])."', 
				".($myLeft+1).", 
				".($myRight+1)."
				)";
		db_query($sql);	
	
				
		$sql = "UNLOCK TABLES";
		db_query($sql);			
	}
	
	/**
	 * update Node
	 *@param $data : array mit dem daten
	 *@param $id : id des node
	 */
	public function update_node($data,$id)
	{
		$sql = "UPDATE ".$this->table_name." 
		SET name = '".utf8_htmlspecialchars($data['name'])."', 
			gtag = '".utf8_htmlspecialchars($data['gtag'])."' ,
			stag = '".utf8_htmlspecialchars($data['stag'])."' ,
			sex = '".utf8_htmlspecialchars($data['sex'])."' ,
			bast_vater = '".utf8_htmlspecialchars($data['bast_vater'])."',
			bast_mutter = '".utf8_htmlspecialchars($data['bast_mutter'])."',
			ehepartner = '".utf8_htmlspecialchars($data['ehepartner'])."',
			ehepartner_sex = '".utf8_htmlspecialchars($data['ehepartner_sex'])."',
			ep_gtag = '".utf8_htmlspecialchars($data['ep_gtag'])."' ,
			ep_stag = '".utf8_htmlspecialchars($data['ep_stag'])."'
			WHERE id = '".$id."' ";
		db_query($sql);			
	}
	
	/**
	 * get_nodedata
	 *@param $id : id des node
	 */
	public function get_nodedata($id)
	{
		$sql = "SELECT * FROM ".$this->table_name." WHERE id = '".$id."' LIMIT 1";
		
		$return = db_fetch_assoc(db_query($sql));
		
		foreach($return as $key => $val) {
			$return2[$key] = utf8_html_entity_decode($val);
  		}
		
		return $return2;			
	}
	
	/**
	 * Delete Node
	 * @param $node_id : id des Knoten
	 * @param $delete_subs : ob die subtrees mitgelöscht werden // fals nicht, wird knoten durch platzhalter ersetzt
	 */
	public function delete_node($node_id=0,$delete_subs=true)
	{
		if($delete_subs)
		{
			$sql = "LOCK TABLE ".$this->table_name." WRITE";
			db_query($sql);
			
			$sql = "SELECT lft, rgt
			FROM ".$this->table_name."
			WHERE id = '".$node_id."'";
			$row = db_fetch_assoc(db_query($sql));
			
			$myLeft = $row['lft'];
			$myRight = $row['rgt'];
			$myWidth = $row['rgt'] - $row['lft'] + 1;
			
			$sql = "DELETE FROM ".$this->table_name." WHERE acctid = '".$this->actual_user."' AND lft BETWEEN ".$myLeft." AND ".$myRight."";
			db_query($sql);
			
			$sql = "UPDATE ".$this->table_name." SET rgt = rgt - ".$myWidth." WHERE acctid = '".$this->actual_user."' AND rgt > ".$myRight."";
			db_query($sql);
			
			$sql = "UPDATE ".$this->table_name." SET lft = lft - ".$myWidth." WHERE acctid = '".$this->actual_user."' AND lft > ".$myRight."";
			db_query($sql);
			
			$sql = "UNLOCK TABLES";
			db_query($sql);
		}
		else
		{
			$sql = "UPDATE ".$this->table_name." SET name = '', 
					gtag = '' ,
					stag = '' ,
					status = '0',
					sex = '0',
					bast_vater = '',
					bast_mutter = '',
					ehepartner = '',
					ep_gtag = '' ,
					ep_stag = ''
					WHERE id = '".$node_id."' ;";
			db_query($sql);
		}
	}
	
	/**
	 * Überprüft ob ein Knoten Söhne hat
	 * @param $node_id : id des Knoten
	 */
	public function has_sons($node_id)
	{
		$count = db_num_rows($this->get_tree(3,$node_id,true));
		
		if($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Gibt die ID des letzten Sohnes zurück
	 * @param $id : id des Knoten
	 */
	public function last_son_id($id)
	{
		$query = $this->get_tree(3,$id,true);
		while($row = db_fetch_assoc($query))
		{
			$return = $row['id'];
		}
		
		return $return;
	}
	
	/**
	 * Gibt die ID des parent zurück
	 * @param $id : id des Knoten
	 */
	public function parent_id($id)
	{
		$path = $this->get_path($id);
		$count = count($path);
		return $path[$count-2]['id'];
	}
	
	/**
	 * Überprüft ob ein User bereits ein Stammbaum angelegt hat
	 *@param $gold : gold welches zu zahlen ist oder 0
	 *@param $gems : gems welche zu zahlen sind oder 0
	 *@param $dps : dps welche zu zahlen sind oder 0
	 */
	public function do_payment($gold=0,$gems=0,$dps=0)
	{
		global $session;
		
		$pointsavailable=$session['user']['donation']-$session['user']['donationspent'];
		
		if( ($session['user']['gold'] >= $gold) and ($session['user']['gems'] >= $gems) and ($pointsavailable >= $dps) )
		{
			$session['user']['gold'] -= $gold;
			$session['user']['gems'] -= $gems;
			$session['user']['donationspent'] += $dps;
			
			($dps > 0) ? debuglog('Gab '.$dps.'DP für Stammbaum') : false;
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Überprüft ob ein User bereits ein Stammbaum angelegt hat
	 */
	public function has_tree()
	{
	
		$sql = "SELECT id
				FROM ".$this->table_name."
				WHERE name = 'ROOT' AND acctid = '".$this->actual_user."'";
		$count = db_num_rows(db_query($sql));
		
		if($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * legt einen Stammbaum an
	 */
	public function make_tree()
	{
		$sql = "INSERT INTO ".$this->table_name."(acctid, name, gtag, stag, status, sex, bast_vater, bast_mutter, ehepartner, ep_gtag, ep_stag, lft, rgt) 
				VALUES (
				'".$this->actual_user."',
				'ROOT',
				NOW(),
				NOW(),
				'0',
				'0',
				'',
				'',
				'',
				NOW(),
				NOW(), 
				'1', 
				'2'
				)";
		db_query($sql);
		
		return $this->has_tree();
	}
	
	/**
	 * löscht einen Stammbaum
	 */
	public function del_tree()
	{
		$sql = "DELETE FROM ".$this->table_name." WHERE acctid = '".$this->actual_user."' ";
		db_query($sql);
		return true;
	}
		
	/**
	 * Liefert ein array aus einem (Teil-)Baum
	 * @param $result mysql-query
	 */
	private function paint_tree_as_array($result)
	{
		$tree = array();
		
		while($row = db_fetch_assoc($result))
		{
			$tree[] = $row;
		}
				
		return $tree;
	}
	
	/**
	 * Liefert einen HTML Stammbaum aus einem (Teil-)Baum
	 * @param $result mysql-query
	 */
	private function paint_tree($result,$editable = false)
	{
		global $session;
			
		$lastdepth = 0;
		$row = db_fetch_assoc($result); // Root
		
		$tree = '`c`bFamilienstammbaum`b`c';
		
		if($editable) 
		{
			$tree .= '`n';
			$tree .= '[<a href="stammbaum.php?op=editnewconfirm&id='.$row['id'].'">Kind</a>]';
			//addnav('','stammbaum.php?op=editold&id='.$row['id'].'');
			//addnav('','stammbaum.php?op=editdel&id='.$row['id'].'');
			addnav('','stammbaum.php?op=editnewconfirm&id='.$row['id'].'');
		}
		
		$root 	= new CStamNode($tree);
		$lastsohn = $root;
		$parent = $root;
		$lastparent[$row['depth']] = $root;
		
		while($row = db_fetch_assoc($result))
		{
			if($row['depth'] == $lastdepth)
			{
				$tree = '`c`b'.$row['name'];
				($row['sex'] == 1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
				$tree .= '`b`c';
				($row['gtag'] != '') ? $tree .= '`n * '.$row['gtag'].'' : false ;
				($row['stag'] != '') ? $tree .= '`n &dagger; '.$row['stag'].'' : false ;
				
				(trim($row['bast_vater']) != '') ? $tree .= '`nVater: '.$row['bast_vater'].'' : false ;
				(trim($row['bast_mutter']) != '') ? $tree .= '`nMutter: '.$row['bast_mutter'].'' : false ;
				
				if(trim($row['ehepartner']) != '')
				{
					if($row['ehepartner_sex']==0)$row['ehepartner_sex'] = ($row['sex'] == 2) ? 1 : 2;
					$tree .= '`n`n&infin;`n`n`c`b'.$row['ehepartner'];
					($row['ehepartner_sex']==1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
					$tree .= '`b`c';
					($row['ep_gtag'] != '') ? $tree .= '`n * '.$row['ep_gtag'].'' : false ;
					($row['ep_stag'] != '') ? $tree .= '`n &dagger; '.$row['ep_stag'].'' : false ;
				}
				
				if($editable) 
				{
					$tree .= '`n';
					$tree .= '[<a href="stammbaum.php?op=editoldconfirm&id='.$row['id'].'">edit</a>]
					&bull; [<a href="stammbaum.php?op=editdelconfirm&id='.$row['id'].'" onclick="return confirm(\'Die Nachkommen werden auch alle gelöscht!\');">del</a>] &bull; ';
					$tree .= '[<a href="stammbaum.php?op=editnewconfirm&id='.$row['id'].'">Kind</a>]';
					$tree .= '[<a href="stammbaum.php?op=editnewparentconfirm&id='.$row['id'].'">Eltern</a>]';
					addnav('','stammbaum.php?op=editoldconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editdelconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editnewconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editnewparentconfirm&id='.$row['id'].'');
				}
				$sohn 	= new CStamNode($tree);
				$lastsohn = $sohn;
				$parent = $lastparent[$row['depth']];
				$parent->addChildNode( $sohn );
				unset($tree);
				unset($sohn);
			}
			else if($row['depth'] > $lastdepth)
			{
				$tree = '`c`b'.$row['name'];
				($row['sex'] == 1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
				$tree .= '`b`c';
				($row['gtag'] != '') ? $tree .= '`n * '.$row['gtag'].'' : false ;
				($row['stag'] != '') ? $tree .= '`n &dagger; '.$row['stag'].'' : false ;
				
				(trim($row['bast_vater']) != '') ? $tree .= '`nVater: '.$row['bast_vater'].'' : false ;
				(trim($row['bast_mutter']) != '') ? $tree .= '`nMutter: '.$row['bast_mutter'].'' : false ;
				
				if(trim($row['ehepartner']) != '')
				{
					if($row['ehepartner_sex']==0)$row['ehepartner_sex'] = ($row['sex'] == 2) ? 1 : 2;
					
					if($row['ehepartner_sex']==0)$row['ehepartner_sex'] = ($row['sex'] == 2) ? 1 : 2;
					$tree .= '`n`n&infin;`n`n`c`b'.$row['ehepartner'];
					($row['ehepartner_sex']==1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
					$tree .= '`b`c';
					($row['ep_gtag'] != '') ? $tree .= '`n * '.$row['ep_gtag'].'' : false ;
					($row['ep_stag'] != '') ? $tree .= '`n &dagger; '.$row['ep_stag'].'' : false ;
				}
				
				if($editable) 
				{
					$tree .= '`n';
					$tree .= '[<a href="stammbaum.php?op=editoldconfirm&id='.$row['id'].'">edit</a>]
					&bull; [<a href="stammbaum.php?op=editdelconfirm&id='.$row['id'].'" onclick="return confirm(\'Die Nachkommen werden auch alle gelöscht!\');">del</a>] &bull; ';
					$tree .= '[<a href="stammbaum.php?op=editnewconfirm&id='.$row['id'].'">Kind</a>]';
					$tree .= '[<a href="stammbaum.php?op=editnewparentconfirm&id='.$row['id'].'">Eltern</a>]';
					addnav('','stammbaum.php?op=editoldconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editdelconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editnewconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editnewparentconfirm&id='.$row['id'].'');
				}
				$sohn 	= new CStamNode($tree);
				$parent = $lastsohn;
				$lastsohn = $sohn;
				$lastparent[$row['depth']] = $parent;
				$parent->addChildNode( $sohn );
				unset($tree);
				unset($sohn);
				
				$lastdepth = $row['depth'];
			}
			else if($row['depth'] < $lastdepth)
			{
				$tree = '`c`b'.$row['name'];
				($row['sex'] == 1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
				$tree .= '`b`c';
				($row['gtag'] != '') ? $tree .= '`n * '.$row['gtag'].'' : false ;
				($row['stag'] != '') ? $tree .= '`n &dagger; '.$row['stag'].'' : false ;
				
				(trim($row['bast_vater']) != '') ? $tree .= '`nVater: '.$row['bast_vater'].'' : false ;
				(trim($row['bast_mutter']) != '') ? $tree .= '`nMutter: '.$row['bast_mutter'].'' : false ;
				
				if(trim($row['ehepartner']) != '')
				{
					if($row['ehepartner_sex']==0)$row['ehepartner_sex'] = ($row['sex'] == 2) ? 1 : 2;
					$tree .= '`n`n&infin;`n`n`c`b'.$row['ehepartner'];
					($row['ehepartner_sex']==1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
					$tree .= '`b`c';
					($row['ep_gtag'] != '') ? $tree .= '`n * '.$row['ep_gtag'].'' : false ;
					($row['ep_stag'] != '') ? $tree .= '`n &dagger; '.$row['ep_stag'].'' : false ;
				}
				
				if($editable) 
				{
					$tree .= '`n';
					$tree .= '[<a href="stammbaum.php?op=editoldconfirm&id='.$row['id'].'">edit</a>]
					&bull; [<a href="stammbaum.php?op=editdelconfirm&id='.$row['id'].'" onclick="return confirm(\'Die Nachkommen werden auch alle gelöscht!\');">del</a>] &bull; ';
					$tree .= '[<a href="stammbaum.php?op=editnewconfirm&id='.$row['id'].'">Kind</a>]';
					$tree .= '[<a href="stammbaum.php?op=editnewparentconfirm&id='.$row['id'].'">Eltern</a>]';
					addnav('','stammbaum.php?op=editoldconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editdelconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editnewconfirm&id='.$row['id'].'');
					addnav('','stammbaum.php?op=editnewparentconfirm&id='.$row['id'].'');
				}
				$sohn 	= new CStamNode($tree);
				$lastsohn = $sohn;
				$parent = $lastparent[$row['depth']];
				$parent->addChildNode( $sohn );
				unset($tree);
				unset($sohn);
				$lastdepth = $row['depth'];
			}
		}
		
		$tree = new CTree( $root );

			$str_out  = '<div style=" padding: 10px; margin: auto;">';
			$str_out .= $tree->draw( CTree::DRAW_RETURN | CTree::DRAW_NOFRAME );
			$str_out .= '</div>';
			$str_out  = '<center>'.($str_out).'</center>';
			return  str_replace(array('&amp;','&sect;'),array('&','§'),$str_out);
	}
	
	/**
	 * Liefert eine Liste aus einem (Teil-)Baum
	 * Für Debug und Testzwecke.
	 * @param $result mysql-query
	 */
	private function paint_tree_as_list($result)
	{
		$tree = 'Diese Darstellung ist nur zum TESTEN!<br /><br /><ul>';
		$lastdepth = 0;
		
		while($row = db_fetch_assoc($result))
		{
			if($row['depth'] == $lastdepth)
			{
				$tree .= '<li>'.$row['name'].'</li>';
			}
			else if($row['depth'] > $lastdepth)
			{
				$tree .= '<ul><li>'.$row['name'].'</li>';
				$lastdepth = $row['depth'];
			}
			else if($row['depth'] < $lastdepth)
			{
				$tree .= '</ul><li>'.$row['name'].'</li>';
				$lastdepth = $row['depth'];
			}
		}
		
		$tree .= '</ul>';
		
		return $tree;
	}
	
	/**
	 * Liefert einen HTML Stammbaum aus einem (Teil-)Baum
	 * @param $result mysql-query
	 */
	public function get_tree_as_admin($editable = false,$acctid)
	{
		global $session;
		
		$result = $this->get_tree(3,0,false,true);	
			
		$lastdepth = 0;
		$row = db_fetch_assoc($result); // Root
		
		$tree = '`c`bFamilienstammbaum`b`c';
		
		if($editable) 
		{
			$tree .= '`n';
			$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das Du ein Eintrag machen willst?\');">Kind</a>]';
			//addnav('','su_stammbaum.php?userid='.$acctid.'&op=editold&id='.$row['id'].'');
			//addnav('','su_stammbaum.php?userid='.$acctid.'&op=editdel&id='.$row['id'].'');
			addnav('','su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&id='.$row['id'].'');
		}
		
		$root 	= new CStamNode($tree);
		$lastsohn = $root;
		$parent = $root;
		$lastparent[$row['depth']] = $root;
		
		while($row = db_fetch_assoc($result))
		{
			if($row['depth'] == $lastdepth)
			{
				$tree = '`c`b'.$row['name'];
				($row['sex'] == 1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
				$tree .= '`b`c';
				($row['gtag'] != '') ? $tree .= '`n * '.$row['gtag'].'' : false ;
				($row['stag'] != '') ? $tree .= '`n &dagger; '.$row['stag'].'' : false ;
				
				(trim($row['bast_vater']) != '') ? $tree .= '`nVater: '.$row['bast_vater'].'' : false ;
				(trim($row['bast_mutter']) != '') ? $tree .= '`nMutter: '.$row['bast_mutter'].'' : false ;
				
				if(trim($row['ehepartner']) != '')
				{
					if($row['ehepartner_sex']==0)$row['ehepartner_sex'] = ($row['sex'] == 2) ? 1 : 2;
					$tree .= '`n`n&infin;`n`n`c`b'.$row['ehepartner'];
					($row['ehepartner_sex']==1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
					$tree .= '`b`c';
					($row['ep_gtag'] != '') ? $tree .= '`n * '.$row['ep_gtag'].'' : false ;
					($row['ep_stag'] != '') ? $tree .= '`n &dagger; '.$row['ep_stag'].'' : false ;
				}
				
				if($editable) 
				{
					$tree .= '`n';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editoldconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das du den Eintrag bearbeiten willst?\');">edit</a>] 
					&bull; [<a href="su_stammbaum.php?userid='.$acctid.'&op=editdelconfirm&id='.$row['id'].'" onclick="return confirm(\'ACHTUNG: Sicher das du den Eintrag und ALLE Nachkommen löschen willst?\');">del</a>] &bull; ';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das Du ein Eintrag machen willst?\');">Kind</a>]';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editnewparentconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das Du ein Eintrag machen willst?\');">Eltern</a>]';
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editoldconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editdelconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editnewparentconfirm&id='.$row['id'].'');
				}
				$sohn 	= new CStamNode($tree);
				$lastsohn = $sohn;
				$parent = $lastparent[$row['depth']];
				$parent->addChildNode( $sohn );
				unset($tree);
				unset($sohn);
			}
			else if($row['depth'] > $lastdepth)
			{
				$tree = '`c`b'.$row['name'];
				($row['sex'] == 1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
				$tree .= '`b`c';
				($row['gtag'] != '') ? $tree .= '`n * '.$row['gtag'].'' : false ;
				($row['stag'] != '') ? $tree .= '`n &dagger; '.$row['stag'].'' : false ;
				
				(trim($row['bast_vater']) != '') ? $tree .= '`nVater: '.$row['bast_vater'].'' : false ;
				(trim($row['bast_mutter']) != '') ? $tree .= '`nMutter: '.$row['bast_mutter'].'' : false ;
				
				if(trim($row['ehepartner']) != '')
				{
					if($row['ehepartner_sex']==0)$row['ehepartner_sex'] = ($row['sex'] == 2) ? 1 : 2;
					$tree .= '`n`n&infin;`n`n`c`b'.$row['ehepartner'];
					($row['ehepartner_sex']==1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
					$tree .= '`b`c';
					($row['ep_gtag'] != '') ? $tree .= '`n * '.$row['ep_gtag'].'' : false ;
					($row['ep_stag'] != '') ? $tree .= '`n &dagger; '.$row['ep_stag'].'' : false ;
				}
				
				if($editable) 
				{
					$tree .= '`n';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editoldconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das du den Eintrag bearbeiten willst?\');">edit</a>] 
					&bull; [<a href="su_stammbaum.php?userid='.$acctid.'&op=editdelconfirm&id='.$row['id'].'" onclick="return confirm(\'ACHTUNG: Sicher das du den Eintrag und ALLE Nachkommen löschen willst?\');">del</a>] &bull; ';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das Du ein Eintrag machen willst?\');">Kind</a>]';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editnewparentconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das Du ein Eintrag machen willst?\');">Eltern</a>]';
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editoldconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editdelconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editnewparentconfirm&id='.$row['id'].'');
				}
				$sohn 	= new CStamNode($tree);
				$parent = $lastsohn;
				$lastsohn = $sohn;
				$lastparent[$row['depth']] = $parent;
				$parent->addChildNode( $sohn );
				unset($tree);
				unset($sohn);
				
				$lastdepth = $row['depth'];
			}
			else if($row['depth'] < $lastdepth)
			{
				$tree = '`c`b'.$row['name'];
				($row['sex'] == 1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
				$tree .= '`b`c';
				($row['gtag'] != '') ? $tree .= '`n * '.$row['gtag'].'' : false ;
				($row['stag'] != '') ? $tree .= '`n &dagger; '.$row['stag'].'' : false ;
				
				(trim($row['bast_vater']) != '') ? $tree .= '`nVater: '.$row['bast_vater'].'' : false ;
				(trim($row['bast_mutter']) != '') ? $tree .= '`nMutter: '.$row['bast_mutter'].'' : false ;
				
				if(trim($row['ehepartner']) != '')
				{
					if($row['ehepartner_sex']==0)$row['ehepartner_sex'] = ($row['sex'] == 2) ? 1 : 2;
					$tree .= '`n`n&infin;`n`n`c`b'.$row['ehepartner'];
					($row['ehepartner_sex']==1) ? $tree .= '  <img src="./images/male.gif" />' : $tree .= '  <img src="./images/female.gif" />' ;
					$tree .= '`b`c';
					($row['ep_gtag'] != '') ? $tree .= '`n * '.$row['ep_gtag'].'' : false ;
					($row['ep_stag'] != '') ? $tree .= '`n &dagger; '.$row['ep_stag'].'' : false ;
				}
				
				if($editable) 
				{
					$tree .= '`n';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editoldconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das du den Eintrag bearbeiten willst?\');">edit</a>] 
					&bull; [<a href="su_stammbaum.php?userid='.$acctid.'&op=editdelconfirm&id='.$row['id'].'" onclick="return confirm(\'ACHTUNG: Sicher das du den Eintrag und ALLE Nachkommen löschen willst?\');">del</a>] &bull; ';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das Du ein Eintrag machen willst?\');">Kind</a>]';
					$tree .= '[<a href="su_stammbaum.php?userid='.$acctid.'&op=editnewparentconfirm&id='.$row['id'].'" onclick="return confirm(\'Sicher das Du ein Eintrag machen willst?\');">Eltern</a>]';
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editoldconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editdelconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editnewconfirm&id='.$row['id'].'');
					addnav('','su_stammbaum.php?userid='.$acctid.'&op=editnewparentconfirm&id='.$row['id'].'');
				}
				$sohn 	= new CStamNode($tree);
				$lastsohn = $sohn;
				$parent = $lastparent[$row['depth']];
				$parent->addChildNode( $sohn );
				unset($tree);
				unset($sohn);
				$lastdepth = $row['depth'];
			}
		}
		
		$tree = new CTree( $root );

			$str_out  = '<div style=" padding: 10px; width: 600px; overflow: -moz-scrollbars-horizontal; overflow-x: auto;">';
			$str_out .= $tree->draw( CTree::DRAW_RETURN | CTree::DRAW_NOFRAME );
			$str_out .= '</div>';
			$str_out  = '<center>'.print_frame($str_out,'',0,true).'</center>';
			return  str_replace(array('&amp;','&sect;'),array('&','§'),$str_out);
	}
}

?>