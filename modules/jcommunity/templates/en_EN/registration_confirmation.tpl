<h2>Activation of your account</h2>

<p>To activate your account, please fill the following form with the key given in the email you should
have received.</p>


{form $form,'jcommunity~registration:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <p>{ctrl_label 'login'} : {ctrl_control 'login'}</p>
    <p>{ctrl_label 'key'} : {ctrl_control 'key'}</p>
</fieldset>
<p>{formsubmit}</p>
{/form}

