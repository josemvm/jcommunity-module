Installation
============

Latest jCommunity version works only with Jelix 1.4 and higher.


Get the module from a zip
-------------------------

Download the package from http://download.jelix.org/forge/jcommunity/
and extract it somewhere.

You can then move modules/jcommunity into a module repository of your application,
or indicate this modules directory into the mainconfig.ini.php, in the modulePath parameter.


Get the module from Composer
----------------------------

In your composer.json, in the require section, indicate:

```
"jelix/jcommunity-module": "1.0"
```

After a `composer update`, you can declare jcommunity in the modulePath parameter
of the configuration. Example:

```
modulePath=(...);app:vendors/jelix/jcommunity-module/modules/
```

About the jauth and jauthdb module
----------------------------------

You should do nothing about them. Uninstall and deactivate them. jCommunity provides its
own sql table, and its own dao. jCommunity provides all needed things, with some different
behaviors.

Configuration
-------------

In the configuration of the application, activate the module and the auth plugin for the
coordinator:

```
[modules]
jcommunity.access = 2
```

Configure also parameters in the mailer section. jCommunity needs to send mail to users.


Install the jcommunity module with the command installmodule in your application:

```
php cmd.php installmodule jcommunity
```


It then creates a `community_users` table. If you have already a table of users, you can
add new fields of `community_users` in your table. You should then override all DAOs of
the jcommunity module to change fieldnames and the table.

The auth coordplugin is automatically activated in your configuration. However,
verify in your ini file `yourapp/var/config/auth.coord.ini.php`, that you have these values: 


```
 driver = Db
        
 on_error_action = "jcommunity~login:out"
        
 bad_ip_action = "jcommunity~login:out"

 persistant_crypt_key=  "INSERT HERE A SENTENCE"

 [Db]
 ; name of the dao to get user datas
 dao = "jcommunity~user"
 form = "jcommunity~account_admin"
```


Integration into your application
---------------------------------

You can integrate the "status" zone into your main template (directly into the template or
via your main response).

```
  $response->body->assignZone('STATUS', 'jcommunity~status');
```

It shows links to the login form, the register form if the user isn't authenticated, or to
the logout page and account page if he is authenticated.



You can change the start action in index/config.ini.php like this:

```
    startModule=jcommunity
    startAction="login:index"
```


if you use significant urls, link urls_account.xml, urls_auth.xml and
urls_registration.xml to the main urls.xml of the application

```
    <url pathinfo="/auth"     module="jcommunity" include="urls_auth.xml"/>
    <url pathinfo="/profile"  module="jcommunity" include="urls_account.xml"/>
    <url pathinfo="/registration"  module="jcommunity" include="urls_registration.xml"/>
```

