<h1>{$user->nickname|eschtml}'s profile</h1>

<table>
<tr>
    <td>Nickname</td> <td></td>
</tr>
{ifuserconnected}
<tr>
    <td>Email</td> <td></td>
</tr>
{/ifuserconnected}
</table>

{if $himself}
<ul>
    <li><a href="{jurl 'jcommunity~account:edit'}">Edit your profile</a></li>
    <li><a href="{jurl 'jcommunity~account:destroy'}">Delete your profile</a></li>
</ul>
{/if}
