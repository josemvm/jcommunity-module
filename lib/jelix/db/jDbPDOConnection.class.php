<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Laurent Jouanneau
* @contributor Gwendal Jouannic
* @copyright  2005-2006 Laurent Jouanneau
* @copyright  2008 Gwendal Jouannic
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDbPDOResultSet extends PDOStatement{
	const FETCH_CLASS = 8;
	protected $_fetchMode = 0;
	public function fetchAll( $fetch_style = jDbPDOConnection::JPDO_FETCH_OBJ, $column_index=0, $ctor_arg=null){
		if($this->_fetchMode){
			if( $this->_fetchMode != jDbPDOConnection::JPDO_FETCH_COLUMN)
				return parent::fetchAll($this->_fetchMode);
			else
				return parent::fetchAll($this->_fetchMode, $column_index);
		}else{
			return parent::fetchAll( jDbPDOConnection::JPDO_FETCH_OBJ);
		}
	}
	public function setFetchMode($mode, $param=null){
		$this->_fetchMode = $mode;
		return parent::setFetchMode($mode, $param);
	}
}
class jDbPDOConnection extends PDO{
	const JPDO_FETCH_OBJ = 5;
	const JPDO_FETCH_ORI_NEXT = 0;
	const JPDO_FETCH_ORI_FIRST = 2;
	const JPDO_FETCH_COLUMN = 7;
	const JPDO_FETCH_CLASS = 8;
	const JPDO_ATTR_STATEMENT_CLASS = 13;
	const JPDO_ATTR_AUTOCOMMIT = 0;
	const JPDO_ATTR_CURSOR = 10;
	const JPDO_CURSOR_SCROLL = 1;
	const JPDO_ATTR_ERRMODE = 3;
	const JPDO_ERRMODE_EXCEPTION = 2;
	const JPDO_MYSQL_ATTR_USE_BUFFERED_QUERY = 1000;
	const JPDO_ATTR_CASE = 8;
	const JPDO_CASE_LOWER = 2;
	private $_mysqlCharsets =array( 'UTF-8'=>'utf8', 'ISO-8859-1'=>'latin1');
	private $_pgsqlCharsets =array( 'UTF-8'=>'UNICODE', 'ISO-8859-1'=>'LATIN1');
	public $profil;
	public $dbms;
	function __construct($profil){
		$this->profil = $profil;
		$this->dbms=substr($profil['dsn'],0,strpos($profil['dsn'],':'));
		$prof=$profil;
		$user= '';
		$password='';
		unset($prof['dsn']);
		if(isset($prof['user'])){
			$user =$prof['user'];
			unset($prof['user']);
		}
		if(isset($prof['password'])){
			$password = $profil['password'];
			unset($prof['password']);
		}
		unset($prof['driver']);
		parent::__construct($profil['dsn'], $user, $password, $prof);
		$this->setAttribute(self::JPDO_ATTR_STATEMENT_CLASS, array('jDbPDOResultSet'));
		$this->setAttribute(self::JPDO_ATTR_ERRMODE, self::JPDO_ERRMODE_EXCEPTION);
		if($this->dbms == 'mysql')
			$this->setAttribute(self::JPDO_MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		if($this->dbms == 'oci')
			$this->setAttribute(self::JPDO_ATTR_CASE, self::JPDO_CASE_LOWER);
		if(isset($prof['force_encoding']) && $prof['force_encoding']==true){
			if($this->dbms == 'mysql' && isset($this->_mysqlCharsets[$GLOBALS['gJConfig']->charset])){
				$this->exec("SET CHARACTER SET '".$this->_mysqlCharsets[$GLOBALS['gJConfig']->charset]."'");
			}elseif($this->dbms == 'pgsql' && isset($this->_pgsqlCharsets[$GLOBALS['gJConfig']->charset])){
				$this->exec("SET client_encoding to '".$this->_pgsqlCharsets[$GLOBALS['gJConfig']->charset]."'");
			}
		}
	}
	public function query(){
		$args=func_get_args();
		switch(count($args)){
		case 1:
			$rs = parent::query($args[0]);
			$rs->setFetchMode(self::JPDO_FETCH_OBJ);
			return $rs;
			break;
		case 2:
			return parent::query($args[0], $args[1]);
			break;
		case 3:
			return parent::query($args[0], $args[1]);
			break;
		default:
			trigger_error('bad argument number in query',E_USER_ERROR);
		}
	}
	public function limitQuery($queryString, $limitOffset = null, $limitCount = null){
		if($limitOffset !== null && $limitCount !== null){
		   if($this->dbms == 'mysql'){
			   $queryString.= ' LIMIT '.intval($limitOffset).','. intval($limitCount);
		   }elseif($this->dbms == 'pgsql'){
			   $queryString.= ' LIMIT '.intval($limitCount).' OFFSET '.intval($limitOffset);
		   }
		}
		$result = $this->query($queryString);
		return $result;
	}
	public function setAutoCommit($state=true){
		$this->setAttribute(self::JPDO_ATTR_AUTOCOMMIT,$state);
	}
	public function lastIdInTable($fieldName, $tableName){
	  $rs = $this->query('SELECT MAX('.$fieldName.') as ID FROM '.$tableName);
	  if(($rs !== null) && $r = $rs->fetch()){
		 return $r->ID;
	  }
	  return 0;
	}
	public function prefixTable($table_name){
		if(!isset($this->profil['table_prefix']))
			return $table_name;
		return $this->profil['table_prefix'].$table_name;
	}
	public function hasTablePrefix(){
		return(isset($this->profil['table_prefix']) && $this->profil['table_prefix']!='');
	}
}