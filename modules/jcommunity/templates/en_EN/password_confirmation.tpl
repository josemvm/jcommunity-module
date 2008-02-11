<h2>Activation of your new password</h2>

<p>To activate your new password, please fill the following form with the key given in the email you should
have received.</p>

{form $form,'jcommunity~password:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <p>{ctrl_label 'login'} : {ctrl_control 'login'}</p>
    <p>{ctrl_label 'key'} : {ctrl_control 'key'}</p>
</fieldset>
<p>{formsubmit}</p>
{/form}

