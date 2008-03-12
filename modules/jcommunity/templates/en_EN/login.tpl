 <div id="loginbox">
    {ifuserconnected}

    {$login}, you are connected.
    <div class="loginbox-links">
        (<a href="{jurl 'jauth~login:out'}">Logout</a>,
        <a href="{jurl 'jcommunity~account:index', array('user'=>$login)}">Your account</a>)
    </div>

    {else}

   <form action="{formurl 'jauth~login:in'}" method="POST">
   <div>
      {formurlparam 'jauth~login:in'}
      <label for="login-login">Login <input type="text" id="login-login" size="8" name="login" value="{$login|eschtml}"/></label>
      <label for="login-password">Password
            <input type="password" size="8" id="login-password" name="password" value="{$password|eschtml}" /></label>
      <label for="rememberMe"><input type="checkbox" name="rememberMe" id="rememberMe" value="1" /> Remember me</label>
      <button type="submit" value="connexion">Ok</button>  
      <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~registration:index'}">Register</a>, 
        <a href="{jurl 'jcommunity~password:index'}">Forgotten password</a>)
      </div>
   </div>
   </form>

   {/ifuserconnected}
 </div>