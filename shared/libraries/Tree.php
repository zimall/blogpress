<?php
/**
 * @name		CodeIgniter Nested Set Tree Library
 * @author		Michael M Chiwere
 * 
 */

if (!defined("BASEPATH")) exit("No direct script access allowed");

class Tree 
{
	
	// default values
	
	
	/**
	 * Constructor, loads dependencies, initializes the library
	 */
	public function __construct()
	{
		$this->ci = &get_instance();
	}
	

/* ******************************************************************* */
/* Tree Constructors */
/* ******************************************************************* */


/* creates a new root record and returns the node 'l'=1, 'r'=2. */
	public function nstNewRoot ( $table, $data=array() )
	{
		$data['l'] = 1;
		$data['r'] = 2;
		$this->ci->db->insert( $table, $data );
		$data['id'] = $this->ci->db->insert_id();
		return $data;
	}


/* creates a new first child of 'node'. */
	public function nstNewFirstChild ( $table, $node, $newnode=array() )
	{
		$newnode['l'] = $node['l']+1;
		$newnode['r'] = $node['l']+2;
		$this->_shiftRLValues($table, $newnode['l'], 2);
		$this->ci->db->insert( $table, $newnode );
		$newnode['id'] = $this->ci->db->insert_id();
		return $newnode;
	}


/* creates a new last child of 'node'. */
	public function nstNewLastChild ( $table, $node, $newnode=array() )
	{
		$newnode['l'] = $node['r'];
		$newnode['r'] = $node['r']+1;
		$this->_shiftRLValues($table, $newnode['l'], 2);
		$this->ci->db->insert( $table, $newnode );
		$newnode['id'] = $this->ci->db->insert_id();
		return $newnode;
	}


	public function nstNewPrevSibling ( $table, $node, $newnode=array() )
	{
		$newnode['l'] = $node['l'];
		$newnode['r'] = $node['l']+1;
		$this->_shiftRLValues($table, $newnode['l'], 2);
		$this->ci->db->insert( $table, $newnode );
		$newnode['id'] = $this->ci->db->insert_id();
		return $newnode;
	}


	public function nstNewNextSibling ( $table, $node, $newnode=array() )
	{
		$newnode['l'] = $node['r']+1;
		$newnode['r'] = $node['r']+2;
		$this->_shiftRLValues($table, $newnode['l'], 2);
		$this->ci->db->insert( $table, $newnode );
		$newnode['id'] = $this->ci->db->insert_id();
		return $newnode;
	}



/* ******************************************************************* */
/* Tree Reorganization */
/* ******************************************************************* */

/* all nstMove... functions return the new position of the moved subtree. */

/* moves the node '$src' and all its children (subtree) that it is the next sibling of '$dst'. */
	public function nstMoveToNextSibling ( $table, $src, $dst )
	{
		return $this->_moveSubtree ( $table, $src, $dst['r']+1 );
	}


/* moves the node '$src' and all its children (subtree) that it is the prev sibling of '$dst'. */
	public function nstMoveToPrevSibling ($table, $src, $dst)
	{
		return $this->_moveSubtree ($table, $src, $dst['l']);
	}


/* moves the node '$src' and all its children (subtree) that it is the first child of '$dst'. */
	public function nstMoveToFirstChild ($table, $src, $dst)
	{
		return $this->_moveSubtree ($table, $src, $dst['l']+1);
	}


/* moves the node '$src' and all its children (subtree) that it is the last child of '$dst'. */
	public function nstMoveToLastChild ($table, $src, $dst, $i)
	{
		return $this->_moveSubtree ($table, $src, $dst['r'], $i);
	}


/* '$src' is the node/subtree, '$to' is its destination l-value */
	public function _moveSubtree ($table, $src, $to, $i=1)
	{ 
		$log = "\t\t\t".'Function: move_subtree: GIVEN src: l = '.$src['l']."\tr = ".$src['r']." and dst: l = $to\n";
		$treesize = $src['r']-$src['l']+1;
		
		if( $treesize <= 0 )
			$log .= "\t\t\t\tTrouble: Tree size cannot be $treesize, iteration $i\n\t";
		
		$log.="\t\t\t\tTree size = src[r] - src[l] + 1  ({$src['r']}-{$src['l']} + 1) = $treesize\n\t";
		$res = $this->_shiftRLValues($table, $to, $treesize);
		$log.=$res['log'];
		if($src['l'] >= $to) // src was shifted too?
		{
			$log.="\t\t\t\tsrc[l] >= to ({$src['l']}>{$to})\n\t";
			$src['l'] += $treesize;
			$src['r'] += $treesize;
			$log.="\t\t\t\t new src[l] = {$src['l']}; new src[r] = {$src['r']}\n\t";
		}
		$log.="\t\t\t\t\tif src[l] >= dst[l] : ".$src['l']." >= $to\t//src was shifted too?\n\t\t\t\t\t\t";
		$log.="then src[l] = src[l] + treesize: = ".$src['l']."\n\t\t\t\t\t\t";
		$log.="and src[r] = src[r] + treesize: = ".$src['r']."\n\t\t\t\t";
		$log.="now there is enough room next to target to move the subtree\n\n\t\t\t\t";
		/* now there's enough room next to target to move the subtree*/
		$newpos = $this->_shiftRLRange($table, $src['l'], $src['r'], $to-$src['l']);
		$log.='New Position: l = '.$newpos['l']."\tr = ".$newpos['r']."\t:\n";
		$log.=$newpos['log']."\n\t\t\t\tcorrect values after source\n\n\t";
		/* correct values after source */
		$res = $this->_shiftRLValues($table, $src['r']+1, -$treesize);
		$log.=$res['log'];
		if($src['l'] <= $to){ // dst was shifted too?
			$newpos['l'] -= $treesize;
			$newpos['r'] -= $treesize;
		}
		$log.="\t\t\t\t\tif src[l] <= dst[l] : ".$src['l']." <= $to\t//dst was shifted too?\n\t\t\t\t\t\t";
		$log.="then newpos[l] = newpos[l] - treesize: = ".$newpos['l']."\n\t\t\t\t\t\t";
		$log.="and newpos[r] = newpos[r] - treesize: = ".$newpos['r']."\n\t\t\t\t";
		$newpos['log'] = $log;
		return $newpos;
	}


/* ******************************************************************* */
/* Tree Destructors */
/* ******************************************************************* */


/* deletes the entire tree structure including all records. */
	public function nstDeleteTree ($table)
	{
		$this->ci->db->truncate($table );
	}


/* deletes the node '$node' and all its children (subtree). */
	public function nstDelete ($table, $node)
	{
		$leftanchor = $node['l'];
		
		$this->ci->db->delete( $table, array( 'l >='=>$node['l'], 'r <='=>$node['r'] ) );
		$this->_shiftRLValues($table, $node['r']+1, $node['l'] - $node['r'] -1);
		
		return $this->nstGetNodeWhere ( $table, array('l <'=>$leftanchor), 'l DESC' );
	}



/* ******************************************************************* */
/* Tree Queries */
/*
 * the following functions return a valid node (L and R-value), 
 * or L=0,R=0 if the result doesn't exist.
 */
/* ******************************************************************* */



/*	
*	returns the first node that matches the '$whereclause'. 
*	The WHERE-caluse can optionally contain ORDER BY or LIMIT clauses too.
*/
	public function nstGetNodeWhere ( $table, $where=array(), $order=FALSE )
	{
		$row['l'] = 0;
		$row['r'] = 0;
		
		if($order) $this->ci->db->order_by($order);
		
		$res = $this->ci->db->get_where($table, $where);
		
		if( $res->num_rows()>0 )
			$row = $res->row_array();
		
		return $row;
	}



/* returns the node that matches the left value 'leftval'. */
	public function nstGetNodeWhereLeft ($table, $leftval)
	{
		return $this->nstGetNodeWhere( $table, array('l'=>$leftval) );
	}


/* returns the node that matches the right value 'rightval'. */
	public function nstGetNodeWhereRight ($table, $rightval)
	{
		return $this->nstGetNodeWhere( $table, array('r'=>$rightval) );
	}


/* returns the first node that matches the '$whereclause' */
	public function nstRoot ($table, $l=1)
	{
		return $this->nstGetNodeWhere ($table, array('l'=>$l) );
	}

	public function nstFirstChild ($table, $node)
	{
		return $this->nstGetNodeWhere ( $table, array( 'l'=>($node['l']+1) ) );
	}

	public function nstLastChild ($table, $node)
	{
		return $this->nstGetNodeWhere ($table, array( 'r'=>($node['r']-1) ) );
	}

	public function nstPrevSibling ($table, $node)
	{
		return $this->nstGetNodeWhere ($table, array( 'r'=>($node['l']-1) ) );
	}

	public function nstNextSibling ($table, $node)
	{
		return $this->nstGetNodeWhere ($table, array( 'l'=>($node['r']+1) ) );
	}

	public function nstAncestor ($table, $node)
	{
		return $this->nstGetNodeWhere ( $table, array( 'l <'=>$node['l'], 'r >'=>$node['r'] ), 'r ASC' );
	}



/* ******************************************************************* */
/* Tree Functions */
/*
 * the following functions return a boolean value
 */
/* ******************************************************************* */


/* only checks, if L-value < R-value (does no db-query)*/
	public function nstValidNode ($table, $node)
	{
		return ($node['l'] < $node['r']);
	}


	public function nstHasAncestor ($table, $node)
	{
		return $this->nstValidNode($table, $this->nstAncestor($table, $node));
	}

	public function nstHasPrevSibling ($table, $node)
	{
		return $this->nstValidNode($table, $this->nstPrevSibling($table, $node));
	}

	public function nstHasNextSibling ($table, $node)
	{
		return $this->nstValidNode($table, $this->nstNextSibling($table, $node));
	}

	public function nstHasChildren ($table='', $node)
	{
		return (($node['r']-$node['l'])>1);
	}

	public function nstIsRoot ($table, $node)
	{
		return ($node['l']==1);
	}

	public function nstIsLeaf ($table, $node)
	{
		return (($node['r']-$node['l'])==1);
	}


/* returns true, if 'node1' is a direct child or in the subtree of 'node2' */
	public function nstIsChild ($node1, $node2)
	{
		return (($node1['l']>$node2['l']) and ($node1['r']<$node2['r']));
	}

	public function nstIsChildOrEqual ($node1, $node2)
	{
		return (($node1['l']>=$node2['l']) and ($node1['r']<=$node2['r']));
	}

	public function nstEqual ($node1, $node2)
	{
		return (($node1['l']==$node2['l']) and ($node1['r']==$node2['r']));
	}


/* ******************************************************************* */
/* Tree Functions */
/*
 * the following functions return an integer value
 */
/* ******************************************************************* */

	public function nstNbChildren ($table, $node)
	{
		return (($node['r']-$node['l']-1)/2);
	}


/* returns node level. (root level = 0)*/
	public function nstLevel ($table, $node)
	{		
		$this->db->from($table);
		$this->ci->db->where( 'l <',$node['l'] );
		$this->ci->db->where( 'r >',$node['l'] );
		return $this->db->count_all_results();
	}



/* ******************************************************************* */
/* Tree Walks  */
/* ******************************************************************* */


/* initializes preorder walk and returns a walk handle */
	public function nstWalkPreorder ($table, $node)
	{
		$this->ci->db->where( array( 'l >='=>$node['l'], 'r <='=>$node['r'] ) );
		$this->ci->db->order_by('l ASC');
		$q = $this->ci->db->get($table);
		
		$res = $q->result_array();

		return array('recset'=>$res,
               'prevl'=>$node['l'], 'prevr'=>$node['r'], // needed to efficiently calculate the level
               'level'=>-2 );
	}

	public function nstWalkNext($table, &$walkhand, $i=0)
	{
		if( $row = $walkhand['recset'][$i] )
		{
			// calc level
			$walkhand['level']+= $walkhand['prevl'] - $row['l'] +2;
			// store current node
			$walkhand['prevl'] = $row['l'];
			$walkhand['prevr'] = $row['r'];
			$walkhand['row']   = $row;
			return array( 'l'=>$row['l'], 'r'=>$row['r'] );
		}
		else return FALSE;
	}

	public function nstWalkAttribute($table, $walkhand, $attribute)
	{
		return $walkhand['row'][$attribute];
	}

	public function nstWalkCurrent($table, $walkhand)
	{
		return array('l'=>$walkhand['prevl'], 'r'=>$walkhand['prevr']);
	}

	public function nstWalkLevel($table, $walkhand)
	{
		return $walkhand['level'];
	}



/* ******************************************************************* */
/* Printing Tools */
/* ******************************************************************* */


/* returns the attribute of the specified node */
	public function nstNodeAttribute ($table, $node, $attribute)
	{
		$q = $this->ci->db->get_where( $table, array( 'l'=>$node['l'] ) );
		if($q->num_rows()>0){
			$row = $q->row_array();
			return $row[$attribute];
		}
		return "";
	}

	public function nstPrintSubtree ($table, $node, $attributes=array())
	{
		$wlk = $this->nstWalkPreorder($table, $node);
		$print='';
		$loop = $wlk['recset'];
		
		foreach( $loop as $k=>$v ){
			$curr = $this->nstWalkNext($table, $wlk, $k);
			// print indentation
			$print.= '<option value="'.$v['id'].'">'.(str_repeat("&nbsp;", $this->nstWalkLevel($table, $wlk)*4)).$v['name'];
			/*// print attributes
			$att = reset($attributes);
			while($att){
				// next line is more efficient:  print ($att.":".nstWalkAttribute($table, $wlk, $att));
				$print.= ($wlk['row'][$att]);
				$att = next($attributes);
			}*/
			$print.= ("</option>");
		}
		return $print.'';
	}
	
	private function sub_array( $table, $node )
	{
		$wlk = $this->nstWalkPreorder($table, $node);
		$array = array();
		$loop = $wlk['recset'];
		foreach( $loop as $k=>$v )
		{
			$curr = $this->nstWalkNext($table, $wlk, $k);
			// add indentation
			$r = $this->nstWalkLevel($table, $wlk)*1;
			//echo $r;
			//if($r==0) $s=''; else $s='&nbsp;';
			if( ($v['r']-$v['l'])>1 ) $array[$k]['bold'] = TRUE;
			else $array[$k]['bold'] = FALSE;
			
			$array[$k]['name'] = ( str_repeat("&nbsp;&nbsp;&nbsp;&nbsp", $r ) ).$v['name'];
			$array[$k]['id'] = $v['id'];
			
			$array[$k]['child'] = FALSE;
			$array[$k]['close'] = FALSE;
		}
		return $array;
	}
	
	private function last($a)
	{
		$l = array_pop($a);
		$a[] = $l;
		if( $l===NULL )
			return -1;
		return $l;
	}
	
	private function sub_fields( $table, $node )
	{
		$wlk = $this->nstWalkPreorder($table, $node);
		$array = array();
		$level = array();
		$l = 0;
		$s='<optgroup>'; $e='</optgroup>';
		$loop = $wlk['recset'];
		foreach( $loop as $k=>$v )
		{
			$curr = $this->nstWalkNext($table, $wlk, $k);
			$cl = $this->nstWalkLevel($table, $wlk)*1;
			
			if($k==0){
				$level[] = $cl;
				$array[$k]['child'] = TRUE;
			}
			$level = array_unique($level);
			sort($level);
			
			
			if( $cl > $this->last($level) ){
				$level[] = $cl;
				$array[$k]['close'] = FALSE;
			}
			
			elseif( $cl < $this->last($level) ){
				$array[$k]['close'] = TRUE;
				array_pop($level);
			}
			else $array[$k]['close'] = FALSE;
			
			if( ($v['r']-$v['l'])>1 ) $array[$k]['child'] = TRUE;
			else $array[$k]['child'] = FALSE;
			
			$array[$k]['bold'] = FALSE;
			$array[$k]['name'] = $v['name'];
			$array[$k]['id'] = $v['id'];
		}
		return $array;
	}

	public function nstPrintSubtreeOLD ($table, $node, $attributes)
	{
		$this->ci->db->order_by('l ASC');
		$q = $this->ci->db->get($table);
		$print='';
		if( $q->num_rows()>0 )
		{
			$res = $q->result_array();
			$level = -1; $prevl = 0;
			foreach( $res as $row )
			{
				// calc level
				if( $row['l'] == ($prevl+1) )
					$level+=1;
				elseif( $row['l'] != ($prevr+1) )
					$level-=1;
				// print indentation
				$print.= str_repeat("&nbsp;", $level*4);
				// print attributes
				$att = reset($attributes);
				while($att){
					$print.=($att.":".$row[$att]);
					$att = next($attributes);
				}
				$print.= ("<br/>");
				$prevl = $row['l'];
				$prevr = $row['r'];
			}
		}
		else $print.= 'Nothing found in '.$table;
		return $print;
	}


/* Prints attributes of the entire tree. */
	public function nstPrintTree ($table, $attributes=array())
	{ 
		return $this->nstPrintSubtree($table, $this->nstRoot($table), $attributes);
	}
	
	public function nstSelectOptions($table, $root_node=1)
	{ 
		return $this->sub_array($table, $this->nstRoot($table,$root_node));
	}



/*
*	returns a string representing the breadcrumbs from $node to $root  
*	Example: "root > a-node > another-node > current-node"
*	Contributed by Nick Luethi
*/
	public function nstBreadcrumbsString ($table, $node)
	{
		// current node
		$ret = $this->nstNodeAttribute ($table, $node, "name");
		// treat ancestor nodes
		while($this->nstAncestor ($table, $node) != array("l"=>0,"r"=>0)){
			$ret = "".$this->nstNodeAttribute($table, $this->nstAncestor($table, $node), "name")." &gt; ".$ret;
			$node = $this->nstAncestor ($table, $node);
		}
		return $ret;
		//return "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;breadcrumb: <font size='1'>".$ret."</font>";
	}
	
	public function nstAllAncestors($table, $node)
	{
		$ret = array();
		while($this->nstAncestor ($table, $node) != array("l"=>0,"r"=>0))
		{
			$ret[] = $this->nstAncestor($table, $node);
			$node = $this->nstAncestor ($table, $node);
		}
		return $ret;
	}

/* ******************************************************************* */
/* internal functions */
/* ******************************************************************* */

	private function _prtError()
	{
		echo "<p>Error: ".mysql_errno().": ".mysql_error()."</p>";
	}



/*********** INTERNAL ROUTINES **********************/	

/* adds '$delta' to all L and R values that are >= '$first'. '$delta' can also be negative. */
	private function _shiftRLValues ($table, $first, $delta)
	{ //print("SHIFT: add $delta to gr-eq than $first <br/>");
		$this->ci->db->query('UPDATE '.$table.' SET l = l+'.$delta.' WHERE l>='.$first);
		$this->ci->db->query('UPDATE '.$table.' SET r = r+'.$delta.' WHERE r>='.$first);
		$log = "\t\t\t".'Function: shift_RL_Values - GIVEN: first = '.$first.' and delta = '.$delta."\n";
		$log.= "\t\t\t\t\t".'UPDATE: SET l = l + '.$delta.' WHERE l>='.$first."\n";
		$log.= "\t\t\t\t\t".'UPDATE: SET r = r + '.$delta.' WHERE r>='.$first."\n";
		return array('log'=>$log);
	}
	

/* adds '$delta' to all L and R values that are >= '$first' and <= '$last'. '$delta' can also be negative. 
   returns the shifted first/last values as node array.
 */
	private function _shiftRLRange ($table, $first, $last, $delta)
	{
		$this->ci->db->query('UPDATE '.$table.' SET l = l+'.$delta.' WHERE l>='.$first.' AND l<='.$last);
		$this->ci->db->query('UPDATE '.$table.' SET r = r+'.$delta.' WHERE r>='.$first.' AND r<='.$last);
		$log = "\t\t\t\t".'Function: shift_RL_Range - GIVEN: first = '.$first.', last = '.$last.' and delta = '.$delta."\n";
		$log.= "\t\t\t\t\t".'UPDATE: SET l = l + '.$delta.' WHERE l>='.$first." AND l<=$last\n";
		$log.= "\t\t\t\t\t".'UPDATE: SET r = r + '.$delta.' WHERE r>='.$first." AND r<=$last\n";
		return array('l'=>$first+$delta, 'r'=>$last+$delta, 'log'=>$log);
	}

}
