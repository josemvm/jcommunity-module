<?php
/**
* @package
* @subpackage test
* @author
* @copyright
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

class sidebarZone extends jZone {
    protected $_tplname='sidebar';

    
    protected function _prepareTpl(){
        $this->_tpl->assignZone('LOGIN','jcommunity~login');
        $this->_tpl->assignZone('MESSENGER','jmessenger~links');
    }
}
?>
