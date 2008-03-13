Hello,

You have asked a new password on our site.

If you want to confirm this change, you should :

- click on this link:
http://{$server}{jurl 'jcommunity~password:confirm', array('conf_login'=>$login,'conf_key'=>$key)}
- Or go on this page http://{$server}{jurl 'jcommunity~password:confirmform'} and
 fill the form with your login and this key : {$key}

After this confirmation, your new password will be :  {$pass}

If you don't want to confirm, ignore this mail, and your
password won't be changed.

See you soon on our site !
