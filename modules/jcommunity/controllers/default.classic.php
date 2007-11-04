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

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        
        return $rep;
    }
    
    /**
    * 
    */
    function login() {
        $rep = $this->getResponse('html');

        $rep->body->assignZone('MAIN','jauth~loginform', array(
            'login' => $this->param('login'),
            'failed' => $this->param('failed'),
            'showRememberMe' => jAuth::isPersistant()));
        return $rep;
    }
}

?>
