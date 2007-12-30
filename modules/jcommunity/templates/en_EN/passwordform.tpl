<h2>Retrieve a new password</h2>

<p>If you have forgotten your password, fill the following form with your login
and with the email you have set in your profil.</p>

{form $form,'jcommunity~password:send', array()}
<fieldset>
    <p>{ctrl_label 'login'} : {ctrl_control 'login'}</p>
    <p>{ctrl_label 'email'} : {ctrl_control 'email'}</p>
</fieldset>
<p>An email will be sent with your new password.</p>
<p>{formsubmit}</p>
{/form}
