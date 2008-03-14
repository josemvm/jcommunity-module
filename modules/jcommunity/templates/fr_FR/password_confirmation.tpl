<h2>Activation de votre nouveau mot de passe</h2>

<p>Un mail vous a été envoyé, contenant votre nouveau mot de passe, et une clé. Pour valider le nouveau
mot de passe, indiquez la clé dans le formulaire suivant.</p>

{form $form,'jcommunity~password:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <p>{ctrl_label 'conf_login'} : {ctrl_control 'conf_login'}</p>
    <p>{ctrl_label 'conf_key'} : {ctrl_control 'conf_key'}</p>
</fieldset>
<p>{formsubmit}</p>
{/form}
