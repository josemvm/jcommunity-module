<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2018 Laurent Jouanneau
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

class jcommunityModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    protected $defaultConfig = array(
        'loginResponse' => 'html',
        'verifyNickname' =>true,
        'passwordChangeEnabled' =>true,
        'accountDestroyEnabled' =>true,
        'useJAuthDbAdminRights' =>false,
        'registrationEnabled' =>true,
        'resetPasswordEnabled' =>true,
        'disableJPref' =>true,
        'publicProperties' =>array('login', 'nickname', 'create_date'),
        'eps'=>array()
    );


    public function getDefaultParameters()
    {
        // retrieve current jcommunity section
        foreach($this->defaultConfig as $name => $value) {
            if ($this->getConfigIni()->getValue($name,'jcommunity') !== null) {
                $this->defaultConfig[$name] = $this->getConfigIni()->getValue($name,'jcommunity');
            }
        }
        return array(
            'manualconfig' => false,
            'masteradmin' => false,
            'migratejauthdbusers' => true,
            'usejpref' => false,
        );
    }

    public function askParameters()
    {
        $this->parameters['eps'] = $this->askEntryPoints(
            'Select entry points on which to setup authentication plugins.',
            'classic',
            true
        );

        $alreadyConfig = false;
        foreach($this->parameters['eps'] as $epId) {
            $ep = $this->getEntryPointsById($epId);
            if ($ep->getConfigIni()->getValue('auth','coordplugins')) {
                $alreadyConfig = true;
                break;
            }
        }
        if ($alreadyConfig) {
            $this->parameters['manualconfig'] = $this->askConfirmation('Do you will modify yourself the existing authcoord.ini.php configuration file?', false);
        }
        else {
            $this->parameters['manualconfig'] = false;
        }
        $this->parameters['migratejauthdbusers'] = $this->askConfirmation('Do you want to migrate users from the jlx_user table to the jcommunity table?', true);
        $this->parameters['masteradmin'] = $this->askConfirmation('Do you use jCommunity with the master_admin module?', false);
        $this->parameters['usejpref'] = $this->askConfirmation('Do you want to use jPref to manage some parameters?', false);

        $this->defaultConfig['registrationEnabled'] = $this->askConfirmation('Is the registration enabled?', $this->defaultConfig['registrationEnabled']);
        $this->defaultConfig['resetPasswordEnabled'] = $this->askConfirmation('Can the user reset his password when he forgot it?', $this->defaultConfig['resetPasswordEnabled']);
        $this->defaultConfig['passwordChangeEnabled'] = $this->askConfirmation('Can the user change his password?', $this->defaultConfig['passwordChangeEnabled']);
        $this->defaultConfig['accountDestroyEnabled'] = $this->askConfirmation('Can the user destroy his account?', $this->defaultConfig['accountDestroyEnabled']);
    }

    public function configure()
    {
        $this->getConfigIni()->setValues($this->defaultConfig, 'jcommunity');
        foreach($this->getParameter('eps') as $epId) {
            $this->configureEntryPoint($epId);
        }
    }

    public function configureEntryPoint($epId) {
        $entryPoint = $this->getEntryPointsById($epId);

        $configIni = $entryPoint->getConfigIni();

        $authconfig = $configIni->getValue('auth','coordplugins');

        if (!$authconfig) {
            $pluginIni = 'auth.coord.ini.php';
            $authconfig = dirname($entryPoint->getConfigFile()).'/auth.coord.ini.php';

            // no configuration, let's install the plugin for the entry point
            $configIni->setValue('auth', $authconfig, 'coordplugins');
            $this->copyFile('var/config/'.$pluginIni, 'appconfig:'.$pluginIni);
        }
        else {
            $conf = $this->getAuthConf($configIni);

            if (!$this->getParameter('manualconfig')) {
                $conf->setValue('driver', 'Db');
                $conf->setValue('dao','jcommunity~user', 'Db');
                $conf->setValue('form','jcommunity~account_admin', 'Db');
                $conf->setValue('error_message', 'jcommunity~login.error.notlogged');
                $conf->setValue('on_error_action', 'jcommunity~login:out');
                $conf->setValue('bad_ip_action', 'jcommunity~login:out');
                $conf->setValue('after_logout', 'jcommunity~login:index');
                $conf->setValue('enable_after_login_override', 'on');
                $conf->setValue('enable_after_logout_override', 'on');
                $conf->setValue('after_login', 'jcommunity~account:show');
                $conf->save();
            }
            else {
                $daoSelector = $conf->getValue('dao', 'Db');
                if (!$daoSelector) {
                    $daoSelector = 'jcommunity~user';
                    $conf->setValue('dao', $daoSelector, 'Db');
                }

                if ($daoSelector == 'jcommunity~user') {
                    $conf->setValue('form','jcommunity~account_admin', 'Db');
                }
                $conf->save();
            }
        }

        if ($this->getParameter('masteradmin')) {
            $conf = $this->getAuthConf($configIni);
            $conf->setValue('after_login', 'master_admin~default:index');
            $conf->save();
            $configIni->setValue('loginResponse', 'htmlauth', 'jcommunity');
        }

        if ($this->getParameter('usejpref')) {
            $this->getConfigIni()->setValue('disableJPref', false, 'jcommunity');
            $prefIni = new \Jelix\IniFile\IniModifier(__DIR__.'/prefs.ini');
            $prefFile = jApp::appConfigPath('preferences.ini.php');
            if (file_exists($prefFile)) {
                $mainPref = new \Jelix\IniFile\IniModifier($prefFile);
                //import this way to not erase changed value.
                $prefIni->import($mainPref);
            }
            $prefIni->saveAs($prefFile);
        }
    }

    protected function getAuthConf($configIni) {
        $authconfig = $configIni->getValue('auth','coordplugins');
        $confPath = jApp::appConfigPath($authconfig);
        $conf = new \Jelix\IniFile\IniModifier($confPath);
        return $conf;
    }


}