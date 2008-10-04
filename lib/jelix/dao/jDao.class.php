<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage dao
* @author     Laurent Jouanneau
* @contributor
* @copyright   2005-2006 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once(JELIX_LIB_PATH.'db/jDb.class.php');
require_once(JELIX_LIB_PATH.'dao/jDaoRecordBase.class.php');
require_once(JELIX_LIB_PATH.'dao/jDaoFactoryBase.class.php');
class jDao{
	public static function create($DaoId, $profil=''){
		if(is_string($DaoId))
			$DaoId = new jSelectorDao($DaoId, $profil);
		$c = $DaoId->getDaoClass();
		if(!class_exists($c,false)){
			jIncluder::inc($DaoId);
		}
		$conn = jDb::getConnection($profil);
		$obj = new $c($conn);
		return $obj;
	}
	public static function get($DaoId, $profil=''){
	   static $_daoSingleton=array();
	   $sel = new jSelectorDao($DaoId, $profil);
	   $DaoId = $sel->toString();
		if(! isset($_daoSingleton[$DaoId])){
			$_daoSingleton[$DaoId] = self::create($sel,$profil);
		}
		return $_daoSingleton[$DaoId];
	}
	public static function createRecord($DaoId, $profil=''){
		$sel = new jSelectorDao($DaoId, $profil);
		$c = $sel->getDaoClass();
		if(!class_exists($c,false)){
			jIncluder::inc($sel);
		}
		$c = $sel->getDaoRecordClass();
		$obj = new $c();
		return $obj;
	}
	public static function createConditions($glueOp = 'AND'){
		$obj = new jDaoConditions($glueOp);
		return $obj;
	}
}