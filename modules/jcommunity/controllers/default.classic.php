<?php
/**
* @package      jcommunity
* @subpackage   
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2007-2012 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        if (jAuth::isConnected()) {
            $tpl->assign('user', jAuth::getUserSession());
        }
        $rep->body->assign('MAIN', $tpl->fetch('startpage'));
        return $rep;
    }

}
