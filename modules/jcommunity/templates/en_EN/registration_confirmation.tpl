<h2>Activation of your account</h2>

<p>An email has been sent to you which contains your new password and a key. You must activate your account
before to authenticate yourself on the web site.</p>

<p>To activate your account, please fill the following form with the key given in the email.</p>

{form $form,'jcommunity~registration:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <p>{ctrl_label 'conf_login'} : {ctrl_control 'conf_login'}</p>
    <p>{ctrl_label 'conf_key'} : {ctrl_control 'conf_key'}</p>
</fieldset>
<p>{formsubmit}</p>
{/form}

