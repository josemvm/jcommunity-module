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


class registration_confirmationZone extends jZone {

   protected $_tplname='password_confirmation';


    protected function _prepareTpl(){
        $form = jForms::get('confirmation');
        if($form == null){
            $form = jForms::create('confirmation');
        }
        $this->_tpl->assign('form',$form->getContainer());
    }

}

?>