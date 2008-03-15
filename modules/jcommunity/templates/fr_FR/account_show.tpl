<h1>Profil de {$user->nickname|eschtml}</h1>

<table>
<tr>
    <td>Pseudonyme</td> <td>{$user->nickname|eschtml}</td>
</tr>
{ifuserconnected}
<tr>
    <td>Email</td> <td>{$user->email|eschtml}</td>
</tr>
{/ifuserconnected}
</table>

{if $himself}
<ul>
    <li><a href="{jurl 'jcommunity~account:edit'}">Editer votre profile</a></li>
    <li><a href="{jurl 'jcommunity~account:destroy'}">Effacer votre profile</a></li>
</ul>
{/if}