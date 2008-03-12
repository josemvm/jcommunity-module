 <div id="loginbox">
    {ifuserconnected}

    {$login}, vous êtes connecté.
    <div class="loginbox-links">
        (<a href="{jurl 'jauth~login:out'}">déconnexion</a>,
        <a href="{jurl 'jcommunity~user:index', array('user'=>$login)}">votre compte</a>)
    </div>

    {else}

   <form action="{formurl 'jauth~login:in'}" method="POST">
   <div>
      {formurlparam 'jauth~login:in'}
      <label for="login-login">Login 
             <input type="text" id="login-login" size="8" name="login" value="{$login|eschtml}"/></label>
      <label for="login-password">Mot de passe
            <input type="password" id="login-password" size="8" name="password" value="{$password|eschtml}" /></label>
      <label for="rememberMe"> <input type="checkbox" name="rememberMe" id="rememberMe" value="1" /> 
             Identification auto</label>
      <button type="submit" value="connexion">Ok</button>
      <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~registration:index'}">S'inscrire</a>, 
        <a href="{jurl 'jcommunity~password:index'}">mot de passe oublié</a>)
      </div>
   </div>
   </form>

    {/ifuserconnected}
 </div>