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


class password_confirmationZone extends jZone {

   protected $_tplname='password_confirmation';


    protected function _prepareTpl(){
        $form = jForms::get('confirmation');
        if($form == null){
            $form = jForms::create('confirmation');
        }
        $this->_tpl->assign('form',$form);
    }

}

?>