<h2>Activation de votre compte</h2>

<p>Pour activer votre compte, indiquez la clé (un mot avec des chiffres et des lettres) qui vous a
donné par mail aprés avoir créer votre compte.</p>

<form action="{formurl 'jcommunity~registration:confirm'}" method="POST">
<fieldset>
   <legend>Activation</legend>
    <p><label for="key">Login :</label> 
        {if isset($form->errors['login'])}
            <input type="text" name="login" id="login" value=""/>
            <span class="error">Erreur :
            {if $form->errors['login'] == 1}
                valeur invalide
            {elseif $form->errors['login'] == 2}
                saisie obligatoire
            {else}
                {$form->errors['login']}
            {/if}</span>
        {else}
            <input type="text" name="login" id="login" value="{$form->datas['login']}"/>
        {/if}
    </p>
    <p><label for="key">Clef d'activation :</label> 
        <input type="text" name="key" id="key" value="" />
        {if isset($form->errors['key'])}<span class="error">Erreur :
            {if $form->errors['key'] == 1}
                valeur invalide
            {elseif $form->errors['key'] == 2}
                saisie obligatoire
            {else}
                {$form->errors['key']}
            {/if}</span>
        {/if}
    </p>
    {formurlparam 'jcommunity~registration:confirm'}
</fieldset>
<p><input type="submit" value="Activer" /></p>
</form>
