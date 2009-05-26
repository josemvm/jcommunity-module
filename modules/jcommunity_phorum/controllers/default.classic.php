<?php
/**
* @package   portail
* @subpackage jcommunity_phorum
* @author    Laurent Jouanneau
* @copyright 2009 xulfr.org
* @link      http://xulfr.org
* @license    All right reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        return $rep;
    }
}

