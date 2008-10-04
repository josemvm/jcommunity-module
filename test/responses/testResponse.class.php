<?php

/**
* @package     
* @subpackage  
* @author      Jouanneau Laurent
* @contributor
* @copyright   2007 Jouanneau laurent
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/

require_once (JELIX_LIB_PATH.'core/response/jResponseHtml.class.php');

class testResponse extends jResponseHtml {


   public $bodyTpl = 'test~main';

   // modifications communes aux actions utilisant cette reponses
   protected function _commonProcess(){
       $this->title .= ($this->title !=''?' - ':'').' Test jCommunity';

       $this->body->assignIfNone('MAIN','<p>Empty page</p>');
       $this->body->assignIfNone('menu','<p></p>');
       $this->body->appendZone('SIDEBAR','test~sidebar');
       $this->body->assignIfNone('page_title','Application Test for jCommunity');
       $this->addCSSLink($GLOBALS['gJConfig']->urlengine['basePath'].'design/screen.css');
   }
}
?>