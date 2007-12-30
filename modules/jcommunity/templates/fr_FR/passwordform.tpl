<h2>Récupération d'un nouveau mot de passe</h2>

<p>Si vous avez oublié votre mot de passe, indiquez ci-dessous votre login
et l'adresse e-mail que vous avez indiqué dans votre profil, lors de votre inscription.</p>

{form $form,'jcommunity~password:send', array()}
<fieldset>
    <p>{ctrl_label 'login'} : {ctrl_control 'login'}</p>
    <p>{ctrl_label 'email'} : {ctrl_control 'email'}</p>
</fieldset>
<p>Un email vous sera envoyé avec votre nouveau mot de passe.</p>
<p>{formsubmit}</p>
{/form}
