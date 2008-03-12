<?php
/**
* @package      jcommunity
* @subpackage   
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2008 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/


class loginZone extends jZone {

   protected $_tplname='login';


    protected function _prepareTpl(){
        if(jAuth::isConnected()) {
            $this->_tpl->assign('login',jAuth::getUserSession ()->login);
        }
        else {
            $this->_tpl->assign('login','');
        }
        $this->_tpl->assignIfNone('password','');
    }
}

?>