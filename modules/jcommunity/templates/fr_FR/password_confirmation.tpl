<h2>Activation de votre nouveau mot de passe</h2>

<p>Pour activer votre nouveau mot de passe, indiquez la clé (un mot avec des chiffres et des lettres) qui vous a
été donné par mail aprés avoir fait votre demande de mot de passe.</p>

{form $form,'jcommunity~password:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <p>{ctrl_label 'login'} : {ctrl_control 'login'}</p>
    <p>{ctrl_label 'key'} : {ctrl_control 'key'}</p>
</fieldset>
<p>{formsubmit}</p>
{/form}
