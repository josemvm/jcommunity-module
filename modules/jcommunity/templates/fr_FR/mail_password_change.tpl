Bonjour,

Vous venez de demander un nouveau mot de passe sur le site.

Si vous voulez confirmer ce changement, soit vous cliquez
sur le lien suivant :
http://{$server}{jurl 'jcommunity~password:confirm', array('login'=>$login,'key'=>$key)}

Soit vous pouvez aller sur la page http://{$server}{jurl 'jcommunity~password:confirmform'} 
et indiquer votre login et la clé d'activation suivante : {$key}.

Une fois confirmé, vos nouvelles informations de connection seront :

- Votre login : {$login} 
- Le mot de passe : {$pass}

Si vous ne voulez pas confirmer, ignorez ce mail,
et votre ancien mot de passe est toujours valide.


À bientôt sur notre site.
