<h2>Creating an account</h2>

<p>To use web site services, register yourself by filling the following form.</p>

<form action="{formurl 'jcommunity~registration:save'}" method="POST">
<fieldset>
   <legend>Informations</legend>
    <p><label for="login">Login:</label> 
        <input type="text" name="login" id="login" value="{$form->datas['login']}"/>
        {if isset($form->errors['login'])}<span class="error">Error :
            {if $form->errors['login'] == 1}
                invalid value
            {elseif $form->errors['login'] == 2}
                field required
            {else}
                {$form->errors['login']}
            {/if}</span>
        {/if}
    </p>
    <p><label for="email">Email:</label> 
        <input type="text" name="email" id="email" value="{$form->datas['email']}" />
        {if isset($form->errors['email'])}<span class="error">Error :
            {if $form->errors['email'] == 1}
                invalid value
            {elseif $form->errors['email'] == 2}
                field required
            {else}
                {$form->errors['email']}
            {/if}</span>
        {/if}
    </p>
    {formurlparam 'jcommunity~registration:save'}
</fieldset>
<p>An email will be sent to you with a link to confirm your registration. After it, 
you could identified yourself on the site.</p>
<p><input type="submit" value="Continue" /></p>
</form>
