<h2>Création d'un compte</h2>

<p>Pour pouvoir profiter au mieux des services du site, inscrivez-vous
en remplissant le formulaire suivant.</p>

<form action="{formurl 'jcommunity~registration_save'}" method="POST">
<fieldset>
   <legend>Informations à indiquer</legend>
    <p><label for="login">Login :</label> 
        <input type="text" name="login" id="login" value="{$form->datas['login']}"/>
        {if isset($form->errors['login'])}<span class="error">Erreur :
            {if $form->errors['login'] == 1}
                valeur invalide
            {elseif $form->errors['login'] == 2}
                saisie obligatoire
            {else}
                {$form->errors['login']}
            {/if}</span>
        {/if}
    </p>
    <p><label for="email">Email :</label> 
        <input type="text" name="email" id="email" value="{$form->datas['email']}" />
        {if isset($form->errors['email'])}<span class="error">Erreur :
            {if $form->errors['email'] == 1}
                valeur invalide
            {elseif $form->errors['email'] == 2}
                saisie obligatoire
            {else}
                {$form->errors['email']}
            {/if}</span>
        {/if}
    </p>
    {formurlparam 'jcommunity~registration_save'}
</fieldset>
<p>Un e-mail vous sera envoyé pour que vous puissiez confirmer votre inscription
et ensuite pouvoir vous identifier sur le site.</p>
<p><input type="submit" value="Continuer" /></p>
</form>
