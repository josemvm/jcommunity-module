<?php
/**
* @author       Laurent Jouanneau <laurent@jelix.org>
* @copyright    2015 Laurent Jouanneau
*
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

namespace Jelix\JCommunity;

class Config
{
    protected $responseType = 'html';

    protected $registrationEnabled = true;

    protected $resetPasswordEnabled = true;

    protected $verifyNickname = true;

    /**
     */
    public function __construct()
    {
        $config = (isset(\jApp::config()->jcommunity) ? \jApp::config()->jcommunity : array());
        if (isset($config['loginResponse'])) {
            $this->responseType = $config['loginResponse'];
        }

        if (isset($config['verifyNickname'])) {
            $this->verifyNickname = $config['verifyNickname'];
        }

        if ((!isset($config['disableJPref']) || $config['disableJPref'] == true) &&
            class_exists('jPref')
        ) {
            $pref = \jPref::get('jcommunity_registrationEnabled');
            if ($pref !== null) {
                $this->registrationEnabled = $pref;
            }
            $pref = \jPref::get('jcommunity_resetPasswordEnabled');
            if ($pref !== null) {
                $this->resetPasswordEnabled = $pref;
            }
        } else {
            if (isset($config['registrationEnabled'])) {
                $this->registrationEnabled = (bool) $config['registrationEnabled'];
            }
            if (isset($config['resetPasswordEnabled'])) {
                $this->resetPasswordEnabled = (bool) $config['resetPasswordEnabled'];
            }
        }
    }

    public function getResponseType()
    {
        return $this->responseType;
    }

    public function isRegistrationEnabled()
    {
        return $this->registrationEnabled;
    }

    public function isResetPasswordEnabled()
    {
        return $this->resetPasswordEnabled;
    }

    public function verifyNickname()
    {
        return $this->verifyNickname;
    }
}
