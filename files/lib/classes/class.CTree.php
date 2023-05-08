<?php

/**
* Mutter-Klasse für Nodes von CTree
* @abstract 
*/
abstract class CNode{
	protected $m_childNodes = array();
	
	/**
	 * Konstuktor
	 *
	 */
	public function __construct(){
		
	}
	
	/**
	 * Referent auf Childnodes holen
	 *
	 * @return array
	 */
	public function &getChildNodes(){
		return $this->m_childNodes;
	}
	
	/**
	 * Anzahl der Childnodes holen
	 *
	 * @return int
	 */
	public function getChildNodeCount(){
		return count($this->m_childNodes);
	}
	
	/**
	 * ChildNode hinzufügen
	 *
	 * @param CNode $c_node
	 */
	public function addChildNode( &$c_node ){
		$this->m_childNodes[] =& $c_node;
	}
	
	/**
	 * Node Zeichen
	 * @abstract 
	 *
	 */
	abstract public function draw();
}


/**
* Klasse um hirarchische Bäume zu Zeichnen
*/
class CTree {
	/** Flag für CSkill::draw
	 * Funktion gibt den HTML-Code für den Baum zurück und Gibt ihn nicht direkt aus
	 */
	const DRAW_RETURN = 1;
	
	/** Flag für CSkill::draw
	 * Gibt keinen Frame aus
	 */
	const DRAW_NOFRAME = 2;
	
	/**
	 * Flag für CSkill::draw
	 * Standard-Flags
	 */
	const DRAW_DEFAULT = 0;
	
	private $m_root		= null;
	private $str_header = '';
	
	
	/**
	 * Konstuktor
	 *
	 * @param CNode $c_node Root-Node
	 */
	public function __construct( $c_node ){
		$this->m_root = $c_node;
	}

	/**
	 * Setzt den Header für den Frame
	 *
	 * @param unknown_type $str_header
	 */
	public function setHeader( $str_header ){
		$this->str_header = $str_header;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param int $int_flags
	 * @return string
	 */
	public function draw( $int_flags=CTree::DRAW_DEFAULT ){
		$str_out = '';
		$str_out .= '<link href="templates/css/tree.css" rel="stylesheet" type="text/css">';
		
		$str_out .= $this->drawNode( $this->m_root );
		
		//Frame zeichnen?
		if( !($int_flags & CTree::DRAW_NOFRAME) ){
			$str_out = print_frame($str_out, $this->str_header, 0, true);
		}
		
		
		if( $int_flags & CTree::DRAW_RETURN ){ 
			return $str_out;
		}
		else{
			output( $str_out );
		}
		
		return '';
	}
	
	private function drawNode( $c_node ){
		$int_childNodes	= $c_node->getChildNodeCount();
        $middle = '';
		//Node zeichnen
		$str_node 	= $c_node->draw();
		
		//abhängigkeiten zeichen
		if( $int_childNodes > 0 ){
			$str_downline = '<div class="her_downline"></div>';
			
			if( $int_childNodes == 1 ){ 
				//Nur 1 childnode
				$str_heredity = '<td valign="top" align="center"><div class="her_downline2"></div></td>';
			}
			else{
				//Mehrere Childnodes
				$left 	= '<td valign="top" align="right"><div class="her_left"></div></td>';
				$right 	= '<td valign="top" align="left"><div class="her_right"></div></td>';

				for( $i=2; $i<$int_childNodes; ++$i ){
					$middle	.= '<td valign="top" align="center"><div class="her_middle"></div><div class="her_downline2"></div></td>';
				}
			
				$str_heredity = $left.$middle.$right;
			}
			$str_heredity = '<tr>'.$str_heredity.'</tr>';
			
			
			//Abhängige Nodes zeichnen
			$str_subNodes = '';
			$arr_subNodes =& $c_node->getChildNodes();
			foreach( $arr_subNodes as $cn){
				$str_subNodes .= '<td valign="top" align="center">'.
									$this->drawNode( $cn )
								.'</td>';
			}
		}
		
		//Rückgabe
		$str_ret = 	'<table width="100%" cellspacing="0" cellpadding="0" border="0"> 
						<tr>
							<td valign="top" align="center" colspan="'.$int_childNodes.'">'.$str_node.$str_downline.'</td>
						</tr>
						'.$str_heredity.
						$str_subNodes.
					'</table>';
		
		return $str_ret;
	}
}
?>