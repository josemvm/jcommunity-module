<h2>Activation de votre compte</h2>

<p>Un email vous a été envoyé. Il contient votre nouveau mot de passe, mais aussi
une clé (un mot avec des chiffres et des lettres). Avant de pouvoir vous 
identifier sur le site, vous devez activer votre compte.</p>

<p>Pour activer votre compte, indiquez la clé ci dessous.</p>

{form $form,'jcommunity~registration:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <p>{ctrl_label 'conf_login'} : {ctrl_control 'conf_login'}</p>
    <p>{ctrl_label 'conf_key'} : {ctrl_control 'conf_key'}</p>
</fieldset>
<p>{formsubmit}</p>
{/form}

