<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Loic Mathaud
* @contributor
* @copyright  2006 Loic Mathaud
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jAppManager{
	public static function clearTemp(){
		jFile::removeDir(JELIX_APP_TEMP_PATH, false);
	}
}