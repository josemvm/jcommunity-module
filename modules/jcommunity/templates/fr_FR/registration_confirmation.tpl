<h2>Activation de votre compte</h2>

<p>Pour activer votre compte, indiquez la clé (un mot avec des chiffres et des lettres) qui vous a
donné par mail aprés avoir créer votre compte.</p>

{form $form,'jcommunity~registration:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <p>{ctrl_label 'conf_login'} : {ctrl_control 'conf_login'}</p>
    <p>{ctrl_label 'conf_key'} : {ctrl_control 'conf_key'}</p>
</fieldset>
<p>{formsubmit}</p>
{/form}

