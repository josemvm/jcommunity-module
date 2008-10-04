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
abstract class jInstallerApp extends jInstallerBase{
	function installModules(){
		throw new Exception("installModules not implemented");
	}
	function uninstallModules(){
		throw new Exception("uninstallModules not implemented");
	}
}