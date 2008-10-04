<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Julien Issler
* @contributor Uriel Corfa, Laurent Jouannneau
* @copyright   2007 Julien Issler, 2007 Emotic SARL, 2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
* @since 1.0
*/
require_once(JELIX_LIB_UTILS_PATH.'jTcpdf.class.php');
class jResponseTcpdf  extends jResponse{
	protected $_type = 'tcpdf';
	public $tcpdf = null;
	public $outputFileName = 'document.pdf';
	public $doDownload = false;
	public function output(){
		if($this->hasErrors()) return false;
		if(!($this->tcpdf instanceof jTcpdf)){
			throw new jException('jelix~errors.reptcpdf.not_a_jtcpdf');
			return false;
		}
		if($this->doDownload)
			$this->tcpdf->Output($this->outputFileName,'D');
		else
			$this->tcpdf->Output($this->outputFileName,'I');
		flush();
		return true;
	}
	public function outputErrors(){
		global $gJConfig;
		$this->clearHttpHeaders();
		$this->_httpStatusCode ='500';
		$this->_httpStatusMsg ='Internal Server Error';
		$this->addHttpHeader('Content-Type','text/plain;charset='.$gJConfig->charset,false);
		$this->sendHttpHeaders();
		if($this->hasErrors()){
			foreach( $GLOBALS['gJCoord']->errorMessages  as $e){
			   echo '['.$e[0].' '.$e[1].'] '.$e[2]." \t".$e[3]." \t".$e[4]."\n";
			}
		}else{
			echo "[unknow error]\n";
		}
	}
	public function initPdf($orientation='P', $unit='mm', $format='A4', $encoding=null){
		$this->tcpdf = new jTcpdf($orientation, $unit, $format, $encoding);
	}
	public function __call($method, $attr){
		if($this->tcpdf !== null){
			return call_user_func_array(array($this->tcpdf, $method), $attr);
		}else{
			throw new jException('jelix~errors.reptcpdf.not_a_jtcpdf');
			return false;
		}
	}
}