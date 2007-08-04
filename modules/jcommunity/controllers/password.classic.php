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

define('COMAUTH_STATUS_VALID',2);
define('COMAUTH_STATUS_MODIFIED',1);
define('COMAUTH_STATUS_NEW',0);
define('COMAUTH_STATUS_DEACTIVATED',-1);
define('COMAUTH_STATUS_DELETED',-2);

class passwordCtrl extends jController {

    /**
    * form to retrieve a lost password
    */
    function index() {
        $rep = $this->getResponse('html');
        return $rep;
    }

    /**
    * send a new password 
    */
    function sendpwd() {
        $rep = $this->getResponse('redirect');
        return $rep;
    }

    /**
    * message which confirm that a new password has been sent
    */
    function pwdsent() {
        $rep = $this->getResponse('html');
        return $rep;
    }

}
?>
