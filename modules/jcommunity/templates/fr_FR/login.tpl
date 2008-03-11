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
      <label>Login <input type="text" size="8" name="login" value="{$login|escxml}"/></label>
      <label>Mot de passe <input type="password" size="8" name="password" value="{$password|escxml}" /></label>
      <button type="submit" value="connexion">Ok</button>
      <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~registration:index'}">S'inscrire</a>, 
        <a href="{jurl 'jcommunity~password:index'}">mot de passe oublié</a>)
      </div>
   </div>

   </form>
    {/ifuserconnected}
 </div>