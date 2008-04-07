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

include(dirname(__FILE__).'/../classes/defines.php');

class accountCtrl extends jController {

    public $pluginParams = array(
      '*'=>array('auth.required'=>true),
      'show'=>array('auth.required'=>false)
    );

    /**
    *
    */
    function show() {
        $rep = $this->getResponse('html');

        $users = jDao::get('jcommunity~user');
        $user = $users->getByLogin($this->param('user'));
        if(!$user || $user->status < JCOMMUNITY_STATUS_VALID) {
            $rep->body->assign('MAIN','<p>'.jLocale::get('account.unknow.user').'</p>');
            return $rep;
        }

        $tpl = new jTpl();
        $tpl->assign('user',$user);
        $tpl->assign('himself', (jAuth::isConnected() && jAuth::getUserSession()->login == $user->login));
        $rep->body->assign('MAIN',$tpl->fetch('account_show'));
        return $rep;
    }

    function edit() {
        $rep = $this->getResponse('html');

        return $rep;
    }

    function save() {
        $rep = $this->getResponse('html');

        return $rep;
    }


    function destroy() {
        $rep = $this->getResponse('html');

        return $rep;
    }


    function dodestroy() {
        $rep = $this->getResponse('html');

        return $rep;
    }

}
