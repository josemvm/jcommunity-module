<?php
/**
* @package      jcommunity
* @subpackage
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2007-2008 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

include(dirname(__FILE__).'/../classes/defines.php');

class passwordCtrl extends jController {

    /**
    * form to retrieve a lost password
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','password');
        return $rep;
    }

    /**
    * send a new password 
    */
    function send() {
        global $gJConfig;
        $rep= $this->getResponse("redirect");
        $rep->action="password:index";

        $form = jForms::fill('password');
        if(!$form->check()){
            return $rep;
        }

        $login = $form->getData('login');
        $user = jAuth::getUser($login);
        if(!$user){
            $form->setErrorOn('login',jLocale::get('password.login.doesnt.exist'));
            return $rep;
        }

        if($user->email != $form->getData('email')){
            $form->setErrorOn('email',jLocale::get('password.email.unknown'));
            return $rep;
        }

        $pass = jAuth::getRandomPassword();
        $key = substr(md5($login.'-'.$pass),1,10);

        $user->new_password = $pass;
        $user->request_date = date('Y-m-d H:i:s');
        jAuth::updateUser($user);

        $mail = new jMailer();
        $mail->From = $gJConfig->mailer['webmasterEmail'];
        $mail->FromName = $gJConfig->mailer['webmasterName'];
        $mail->Sender = $gJConfig->mailer['webmasterEmail'];
        $mail->Subject = jLocale::get('password.mail.pwd.change.subject');

        $tpl = new jTpl();
        $tpl->assign(compact('login','pass','key'));
        $tpl->assign('server',$_SERVER['SERVER_NAME']);
        $mail->Body = $tpl->fetch('mail_password_change', 'text');

        $mail->AddAddress($user->email);
        //$mail->SMTPDebug = true;
        $mail->Send();

        $rep->action="password:pwdsent";
        return $rep;
    }

    /**
    * message which confirm that a new password has been sent
    */
    function pwdsent() {
        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','passwordsent');
        return $rep;
    }

    /**
    * form to enter the confirmation key
    * to activate the account
    */
    function confirmform() {
        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','password_confirmation');
        return $rep;
    }

    /**
    * activate an account. the key should be given as a parameter
    */
    function confirm() {
        $rep= $this->getResponse("redirect");
        $rep->action="password:confirmform";

        $form = jForms::fill('confirmation');
        if(!$form->check()){
            return $rep;
        }

        $login = $form->getData('login');
        $user = jAuth::getUser($login);
        if(!$user){
            $form->setErrorOn('login',jLocale::get('password.form.confirm.login.doesnt.exist'));
            return $rep;
        }

        if($user->status != JCOMMUNITY_STATUS_PWD_CHANGED) {
            jForms::destroy('confirmation');
            $rep = $this->getResponse('html');
            $rep->body->assignZone('MAIN','passwordok', array('already'=>true));
            return $rep;
        }

        if($form->getData('key') == $user->keyactivate) {
            $form->setErrorOn('key',jLocale::get('password.form.confirm.bad.key'));
            return $rep;
        }

        if($form->getData('key') == $user->keyactivate) {
            $user->status = JCOMMUNITY_STATUS_VALID;
            jAuth::updateUser($user);
            $rep->action="password:confirmok";
            return $rep;
        }
        else {
    }

    /**
    * Page which confirm that the account is activated
    */
    function confirmok() {
        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','passwordok');
        return $rep;
    }


}
?>