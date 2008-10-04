<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
abstract class jInstallerBase{
	public $reporter;
	function __construct($reporter, $basePath){
		$this->reporter = $reporter;
		$this->basePath = $basePath;
	}
	abstract function install();
	abstract function uninstall();
	function execSQLScript($name, $profil=''){
		$tools = jDb::getTools($profil);
		$p = jDb::getProfil($profil);
		$driver = $p['driver'];
		if($driver == 'pdo'){
			preg_match('/^(\w+)\:.*$/',$p['dsn'], $m);
			$driver = $m[1];
		}
		$tools->execSQLScript($this->basePath.$name.'.'.$driver.'.sql');
	}
	function getConfig($filename){
		return new jIniFileModifier(JELIX_APP_CONFIG_PATH.$filename);
	}
	function copyDirectoryContent($sourcePath, $targetPath){
		jFile::createDir($targetPath);
		$dir = new DirectoryIterator($sourcePath);
		foreach($dir as $dirContent){
			if($dirContent->isFile()){
				copy($dirContent->getPathName(), $targetPath.substr($dirContent->getPathName(), strlen($dirContent->getPath())));
			} else{
				if(!$dirContent->isDot() && $dirContent->isDir()){
					$newTarget = $targetPath.substr($dirContent->getPathName(), strlen($dirContent->getPath()));
					$this->copyDirectoryContent($dirContent->getPathName(),$newTarget);
				}
			}
		}
	}
}