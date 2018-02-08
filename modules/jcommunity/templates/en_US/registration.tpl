<div class="jcommunity-box jcommunity-account">
<h1>Creating an account</h1>

<p>Register yourself by filling the following form.</p>

<fieldset>
    <legend>Informations</legend>
    {formfull $form,'jcommunity~registration:save', array()}
    <p>An email will be sent to you with a link and a key to confirm your registration. After it,
    you could identified yourself on the site.</p>
</fieldset>
<p><a href="{jurl 'jcommunity~login:index'}">Cancel and return to the login form</a></p>
</div>
