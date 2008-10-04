<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  db
* @author      Laurent Jouanneau
* @contributor Yannick Le Guédart, Laurent Raufaste
* @copyright   2005-2007 Laurent Jouanneau, 2008 Laurent Raufaste
*
* Some of this classes were get originally from the Copix project
* (CopixDbConnection, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this classes were adapted/improved for Jelix by Laurent Jouanneau
*
* @link     http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
abstract class jDbConnection{
	public $profil;
	public $dbms;
	public $msgError = '';
	public $lastQuery;
	private $_autocommit = true;
	protected $_connection = null;
	function __construct($profil){
		$this->profil = & $profil;
		$this->dbms = $profil['driver'];
		$this->_connection = $this->_connect();
	}
	function __destruct(){
		if($this->_connection !== null){
			$this->_disconnect();
		}
	}
	public function query($queryString){
		$this->lastQuery = $queryString;
		$result = $this->_doQuery($queryString);
		return $result;
	}
	public function limitQuery($queryString, $limitOffset, $limitCount){
		$this->lastQuery = $queryString;
		$result = $this->_doLimitQuery($queryString, intval($limitOffset), intval($limitCount));
		return $result;
	}
	public function exec($query){
		$this->lastQuery = $query;
		$result = $this->_doExec($query);
		return $result;
	}
	public function quote($text, $checknull=true){
		if($checknull)
			return(is_null($text) ? 'NULL' : "'".$this->_quote($text)."'");
		else
			return "'".$this->_quote($text)."'";
	}
	public function prefixTable($table_name){
		if(!isset($this->profil['table_prefix']))
			return $table_name;
		return $this->profil['table_prefix'].$table_name;
	}
	public function hasTablePrefix(){
		return(isset($this->profil['table_prefix']) && $this->profil['table_prefix'] != '');
	}
	public function setAutoCommit($state=true){
		$this->_autocommit = $state;
		$this->_autoCommitNotify($this->_autocommit);
	}
	abstract public function beginTransaction();
	abstract public function commit();
	abstract public function rollback();
	abstract public function prepare($query);
	abstract public function errorInfo();
	abstract public function errorCode();
	abstract public function lastInsertId($fromSequence='');
	public function getAttribute($id){ return '';}
	public function setAttribute($id, $value){}
	public function lastIdInTable($fieldName, $tableName){
		$rs = $this->query('SELECT MAX('.$fieldName.') as ID FROM '.$tableName);
		if(($rs !== null) && $r = $rs->fetch()){
			return $r->ID;
		}
		return 0;
	}
	abstract protected function _autoCommitNotify($state);
	abstract protected function _connect();
	abstract protected function _disconnect();
	abstract protected function _doQuery($queryString);
	abstract protected function _doExec($queryString);
	abstract protected function _doLimitQuery($queryString, $offset, $number);
	protected function _quote($text){
		return addslashes($text);
	}
}
abstract class jDbResultSet implements Iterator{
	const FETCH_CLASS = 8;
	protected $_idResult=null;
	protected $_fetchMode = 0;
	protected $_fetchModeParam = '';
	function __construct(  $idResult){
		$this->_idResult = $idResult;
	}
	function __destruct(){
		if($this->_idResult){
			$this->_free();
			$this->_idResult = null;
		}
	}
	public function id(){ return $this->_idResult;}
	public function setFetchMode($fetchmode, $param=null){
		$this->_fetchMode = $fetchmode;
		$this->_fetchModeParam =$param;
	}
	public function fetch(){
		$result = $this->_fetch();
		if($result && $this->_fetchMode == self::FETCH_CLASS && !($result instanceof $this->_fetchModeParam)){
			$values = get_object_vars($result);
			$o = $this->_fetchModeParam;
			$result = new $o();
			foreach( $values as $k=>$value){
				$result->$k = $value;
			}
		}
		return $result;
	}
	public function fetchAll(){
		$result=array();
		while($res =  $this->fetch()){
			$result[] = $res;
		}
		return $result;
	}
	public function getAttribute($attr){return null;}
	public function setAttribute($attr, $value){}
	abstract public function bindColumn($column, &$param , $type=null);
	abstract public function bindParam($parameter, &$variable , $data_type =null, $length=null,  $driver_options=null);
	abstract public function bindValue($parameter, $value, $data_type);
	abstract public function columnCount();
	abstract public function execute($parameters=null);
	abstract public function rowCount();
	abstract protected function _free();
	abstract protected function _fetch();
	abstract protected function _rewind();
	protected $_currentRecord = false;
	protected $_recordIndex = 0;
	public function current(){
		return $this->_currentRecord;
	}
	public function key(){
		return $this->_recordIndex;
	}
	public function next(){
		$this->_currentRecord =  $this->fetch();
		if($this->_currentRecord)
			$this->_recordIndex++;
	}
	public function rewind(){
		$this->_rewind();
		$this->_recordIndex = 0;
		$this->_currentRecord =  $this->fetch();
	}
	public function valid(){
		return($this->_currentRecord != false);
	}
}
class jDb{
	static private $_profils =  null;
	static private $_cnxPool = array();
	public static function getConnection($name = null){
		$profil = self::getProfil($name);
		if(!$name){
			$name = $profil['name'];
		}
		if(!isset(self::$_cnxPool[$name])){
			self::$_cnxPool[$name] = self::_createConnector($profil);
		}
		return self::$_cnxPool[$name];
	}
	public static function getDbWidget($name=null){
		$dbw = new jDbWidget(self::getConnection($name));
		return $dbw;
	}
	public static function getTools($name=null){
		$profil = self::getProfil($name);
		$driver = $profil['driver'];
		if($driver == 'pdo'){
			preg_match('/^(\w+)\:.*$/',$profil['dsn'], $m);
			$driver = $m[1];
		}
		global $gJConfig;
		require_once($gJConfig->_pluginsPathList_db[$driver].$driver.'.dbtools.php');
		$class = $driver.'DbTools';
		$cnx = self::getConnection($name);
		$tools = new $class($cnx);
		return $tools;
	}
	public static function getProfil($name='', $nameIsProfilType=false){
		global $gJConfig;
		if(self::$_profils === null){
			self::$_profils = parse_ini_file(JELIX_APP_CONFIG_PATH.$gJConfig->dbProfils , true);
		}
		if($name == ''){
			if(isset(self::$_profils['default']))
				$name=self::$_profils['default'];
			else
				throw new jException('jelix~db.error.default.profil.unknow');
		}elseif($nameIsProfilType){
			if(isset(self::$_profils[$name]) && is_string(self::$_profils[$name])){
				$name = self::$_profils[$name];
			}else{
				throw new jException('jelix~db.error.profil.type.unknow',$name);
			}
		}
		if(isset(self::$_profils[$name]) && is_array(self::$_profils[$name])){
			self::$_profils[$name]['name'] = $name;
			return self::$_profils[$name];
		}else{
			throw new jException('jelix~db.error.profil.unknow',$name);
		}
	}
	public function testProfil($profil){
		try{
			self::_createConnector($profil);
			$ok = true;
		}catch(Exception $e){
			$ok = false;
		}
		return $ok;
	}
	private static function _createConnector($profil){
		if($profil['driver'] == 'pdo'){
			$dbh = new jDbPDOConnection($profil);
			return $dbh;
		}else{
			global $gJConfig;
			$p = $gJConfig->_pluginsPathList_db[$profil['driver']].$profil['driver'];
			require_once($p.'.dbconnection.php');
			require_once($p.'.dbresultset.php');
			$class = $profil['driver'].'DbConnection';
			$dbh = new $class($profil);
			return $dbh;
		}
	}
	public static function createVirtualProfile($name, $params){
		if($name == ''){
		   throw new jException('jelix~db.error.virtual.profile.no.name');
		}
		if(! is_array($params)){
		   throw new jException('jelix~db.error.virtual.profile.invalid.params', $name);
		}
		if(self::$_profils === null){
			self::$_profils = parse_ini_file(JELIX_APP_CONFIG_PATH . $gJConfig->dbProfils, true);
		}
		self::$_profils[$name] = $params;
		unset(self::$_cnxPool[$name]);
	}
}