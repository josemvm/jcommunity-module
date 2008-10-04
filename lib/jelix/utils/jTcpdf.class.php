<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Julien Issler
* @contributor Laurent Jouanneau
* @copyright   2007-2008 Julien Issler, 2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
* @since 1.0
*/
require_once(LIB_PATH.'tcpdf/tcpdf.php');
class jTcpdf extends TCPDF{
	public function __construct($orientation='P', $unit='mm', $format='A4', $encoding=null){
		if($encoding === null)
			$encoding = $GLOBALS['gJConfig']->charset;
		parent::__construct($orientation, $unit, $format,($encoding == 'UTF-8' || $encoding == 'UTF-16'), $encoding);
		$this->setHeaderFont(array('vera','',10));
		$this->setFooterFont(array('vera','',10));
	}
	public function Error($msg){
		throw new Exception($msg);
	}
	public function saveToDisk($filename,$path){
		$data = $this->Output('','S');
		if(!is_dir($path)){
			throw new jException('jelix~errors.file.directory.notexists',array($path));
		}
		if(file_put_contents(realpath($path).'/'.$filename, $data)){
		   return true;
		}
		if(!is_writable($path)){
		   throw new jException('jelix~errors.file.directory.notwritable',array($path));
		}
		throw new jException('jelix~errors.file.write.error',array($path.'/'.$filename,''));
	}
}