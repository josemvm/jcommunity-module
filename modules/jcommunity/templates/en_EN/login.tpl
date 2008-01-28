 <div id="loginbox">
    {ifuserconnected}
    Connected. <a href="{jurl 'jauth~login:out'}">disconnection</a> <a href="{jurl 'jcommunity~user:index'}">Your account</a>
    {/ifuserconnected}
    {ifusernotconnected}
   <form action="{formurl 'jauth~login:in'}" method="POST">
   <div>
        {formurlparam 'jauth~login:in'}
      <label>Login <input type="text" size="8" name="login" value="{$login|escxml}"/></label>
      <label>Password <input type="password" size="8" name="password" value="{$password|escxml}" /></label>
      <button type="submit" value="connection">Ok</button>  
        (<a href="{jurl 'jcommunity~registration:index'}">Register</a>, 
        <a href="{jurl 'jcommunity~password:index'}">Forgotten password</a>)
   </div>

   </form>
    {/ifusernotconnected}
 </div>