<?php
/**
* @package      jcommunity
* @subpackage   
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2007 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/


class passwordOkZone extends jZone {

   protected $_tplname='password_ok';


   protected function _prepareTpl(){
        $this->_tpl->assign('already',$this->getParam('already'));
   }
}

?>