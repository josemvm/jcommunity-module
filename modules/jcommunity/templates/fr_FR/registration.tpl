<h2>Création d'un compte</h2>

<p>Pour pouvoir profiter au mieux des services du site, inscrivez-vous
en remplissant le formulaire suivant.</p>

{form $form,'jcommunity~registration:save', array()}
<fieldset>
    <legend>Informations</legend>
    <p>{ctrl_label 'login'} : {ctrl_control 'login'}</p>
    <p>{ctrl_label 'email'} : {ctrl_control 'email'}</p>
</fieldset>
<p>Un e-mail vous sera envoyé pour que vous puissiez confirmer votre inscription
et ensuite pouvoir vous identifier sur le site.</p>
<p>{formsubmit}</p>
{/form}