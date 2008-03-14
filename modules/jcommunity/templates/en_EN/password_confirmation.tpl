<h2>Activation of your new password</h2>

<p>An email has been sent to you which contains your new password and a key.
In order to confirm the password change. you should indicate the key in the following form.
After that, the new password given in the mail will be valid.</p>

{form $form,'jcommunity~password:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <p>{ctrl_label 'conf_login'} : {ctrl_control 'conf_login'}</p>
    <p>{ctrl_label 'conf_key'} : {ctrl_control 'conf_key'}</p>
</fieldset>
<p>{formsubmit}</p>
{/form}

