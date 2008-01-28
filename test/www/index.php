<?php
/**
* @package  test
* @subpackage www
* @author
* @contributor
* @copyright
*/

require_once ('../../lib/jelix/init.php');

require_once ('../application.init.php');

require_once (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

$config_file = 'index/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());

?>
