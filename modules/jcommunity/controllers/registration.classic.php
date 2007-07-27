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

define('COMAUTH_STATUS_VALID',2);
define('COMAUTH_STATUS_MODIFIED',1);
define('COMAUTH_STATUS_NEW',0);
define('COMAUTH_STATUS_DEACTIVATED',-1);
define('COMAUTH_STATUS_DELETED',-2);

class registrationCtrl extends jController {
    /**
    * registration form
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','registration');
        return $rep;
    }

    protected function randomPassword(){
        $letter = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $pass = '';
        for($i=0;$i<10;$i++){
            $pass .= $letter{rand(0,61)};
        }
        return $pass;
    }

    /**
    *
    */
    function save() {
        $form = jForms::fill('registration');
        if(!$form->check()){
            $rep= $this->getResponse("redirect");
            $rep->action="registration_index";
            return $rep;
        }

        $login = $form->getData('login');
        if(jAuth::getUser($login)){
            $form->setErrorOn('login',jLocale::get('register.form.login.exists'));
            $rep= $this->getResponse("redirect");
            $rep->action="registration_index";
            return $rep;
        }

        $pass = $this->randomPassword();
        $key = substr(md5($login.'-'.$pass),1,10);

        $user = jAuth::createUserObject($login,$pass);
        $user->email = $form->getData('email');
        $user->pseudo = $login;
        $user->status = COMAUTH_STATUS_NEW;
        $user->keyactivate = $key;
        jAuth::saveNewUser($user);

        $mail = new jMailer();
        $mail->From = 'webmaster@xulfr.org';
        $mail->FromName = 'Webmaster Xulfr';
        $mail->Sender = 'webmaster@xulfr.org';
        $mail->Subject = jLocale::get('register.mail.new.subject');

        $tpl = new jTpl();
        $tpl->assign(compact('login','pass','key'));
        $tpl->assign('server',$_SERVER['SERVER_NAME']);
        $mail->Body = $tpl->fetch('mail_registration', 'text');

        $mail->AddAddress($user->email);
        //$mail->SMTPDebug = true;
        $mail->Send();

        $rep= $this->getResponse("redirect");
        $rep->action="registration_saveinfo";
        return $rep;
    }

    /**
    *
    */
    function saveinfo() {
        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','registrationsent');
        return $rep;
    }


    /**
    *
    */
    function confirmform() {
        $form = jForms::get('confirm');
        if($form == null){
            $form = jForms::create('confirm');
        }

        $rep = $this->getResponse('html');
        
        return $rep;
    }

    /**
    *
    */
    function confirm() {
        



        $rep = $this->getResponse('redirect');
        $rep->action="registration_confirmok";
        return $rep;
    }

    /**
    *
    */
    function confirmok() {
        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','registrationok');
        return $rep;
    }

    /**
    *
    */
    function getpwd() {
        $rep = $this->getResponse('html');
        
        return $rep;
    }

    /**
    *
    */
    function sendpwd() {
        $rep = $this->getResponse('redirect');
        
        return $rep;
    }

    /**
    *
    */
    function pwdsent() {
        $rep = $this->getResponse('html');
        
        return $rep;
    }
}
?>
