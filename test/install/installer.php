<?php
/**
* @package   test
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/


require_once (__DIR__.'/../application.init.php');
jApp::setEnv('install');

jAppManager::close();

// launch the low-level migration
$migrator = new \jInstallerMigration(new textInstallReporter('notice', 'Low-level migration'));
$migrator->migrate();

// we can now launch the installer/updater
$installer = new jInstaller(new textInstallReporter());
$installer->installApplication();

try {
    jAppManager::clearTemp();
}
catch(Exception $e) {
    echo "WARNING: temporary files cannot be deleted because of this error: ".$e->getMessage().".\nWARNING: Delete temp files by hand immediately!\n";
}
jAppManager::open();
