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
      <label>Login <input type="text" size="8" name="login" value="{$login|escxml}"/></label>
      <label>Password <input type="password" size="8" name="password" value="{$password|escxml}" /></label>
      <button type="submit" value="connection">Ok</button>  
        <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~registration:index'}">Register</a>, 
        <a href="{jurl 'jcommunity~password:index'}">Forgotten password</a>)</div>
   </div>

   </form>
    {/ifuserconnected}
 </div>