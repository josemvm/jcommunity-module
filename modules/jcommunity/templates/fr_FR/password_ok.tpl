<h2>Activation de votre nouveau mot de passe</h2>
{if $status == 1}
<p>Votre nouveau mot de passe est déjà activé. Vous pouvez vous identifier sur le site.</p>
{else}
{if $status == 2}
<p>L'activation n'est pas possible : la periode de validité de la clé a expirée.
Si vous voulez récupérer votre mot de passe, <a href="{jurl 'jcommunity~password:index'}">refaîte une demande</a>.</p>
<p>Vous pouvez toutefois vous identifier sur le site avec votre ancien mot de passe.</p>
{else}
<p>Votre nouveau mot de passe est maintenant activé. Vous pouvez vous identifier sur le site.</p>
{/if}
{/if}
