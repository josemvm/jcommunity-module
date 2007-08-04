<h2>Activation of your account</h2>

<p>To activate your account, please fill the following form with the key given in the email you should
have received.</p>

<form action="{formurl 'jcommunity~registration_confirm'}" method="POST">
<fieldset>
   <legend>Activation</legend>
    <p><label for="key">Login:</label> 
        {if isset($form->errors['login'])}
            <input type="text" name="login" id="login" value=""/>
            <span class="error">Erreur :
            {if $form->errors['login'] == 1}
                invalid value
            {elseif $form->errors['login'] == 2}
                field required
            {else}
                {$form->errors['login']}
            {/if}</span>
        {else}
            <input type="text" name="login" id="login" value="{$form->datas['login']}"/>
        {/if}
    </p>
    <p><label for="key">Key:</label> 
        <input type="text" name="key" id="key" value="" />
        {if isset($form->errors['key'])}<span class="error">Erreur :
            {if $form->errors['key'] == 1}
                invalid value
            {elseif $form->errors['key'] == 2}
                field required
            {else}
                {$form->errors['key']}
            {/if}</span>
        {/if}
    </p>
    {formurlparam 'jcommunity~registration_confirm'}
</fieldset>
<p><input type="submit" value="Activate" /></p>
</form>
