<?php 
require_once (JELIX_LIB_RESPONSE_PATH.'jResponseHtml.class.php');

class myHtmlResponse extends jResponseHtml {

    public $bodyTpl = '%%appname%%~main';

    protected function _commonProcess() {
        $this->body->assignIfNone('MAIN','<p>no content</p>');
    }
}
?>
