<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage dao
* @author     Croes Gérald, Laurent Jouanneau
* @contributor Laurent Jouanneau
* @copyright  2001-2005 CopixTeam, 2005-2006 Laurent Jouanneau
* @copyright  2008 Thomas
* This classes was get originally from the Copix project (CopixDAOSearchConditions, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this classes was adapted for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDaoCondition{
	public $parent = null;
	public $conditions = array();
	public $group = array();
	public $glueOp;
	function __construct($glueOp='AND', $parent =null){
		$this->parent = $parent;
		$this->glueOp = $glueOp;
	}
}
class jDaoConditions{
	public $condition;
	public $order = array();
	public $group = array();
	private $_currentCondition;
	function __construct($glueOp = 'AND'){
		$this->condition = new jDaoCondition($glueOp);
		$this->_currentCondition = $this->condition;
	}
	function addItemOrder($field_id, $way='ASC'){
		$this->order[$field_id]=$way;
	}
	function addItemGroup($field_id){
		$this->group[] = $field_id;
	}
	function isEmpty(){
		return(count($this->condition->group) == 0) &&
		(count($this->condition->conditions) == 0) &&
		(count($this->order) == 0) ;
	}
	function hasConditions(){
		return(count($this->condition->group) || count($this->condition->conditions));
	}
	function startGroup($glueOp = 'AND'){
		$cond= new jDaoCondition($glueOp, $this->_currentCondition);
		$this->_currentCondition->group[] = $cond;
		$this->_currentCondition = $cond;
	}
	function endGroup(){
		if($this->_currentCondition->parent !== null){
			$this->_currentCondition = $this->_currentCondition->parent;
		}
	}
	function addCondition($field_id, $operator, $value, $foo = false){
		$this->_currentCondition->conditions[] = array(
		   'field_id'=>$field_id,
		   'value'=>$value,
		   'operator'=>$operator, 'isExpr'=>$foo);
	}
}