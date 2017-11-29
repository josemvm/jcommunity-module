Installation
============

Latest jCommunity version works only with Jelix 1.6 and higher.


Get the module from a zip
-------------------------

Download the package from http://download.jelix.org/forge/jcommunity/
and extract it somewhere.

You can then move modules/jcommunity into a module repository of your application,
or declare the directory into the mainconfig.ini.php (for Jelix 1.6), or into
the application.init.php (for Jelix 1.7+).

Get the module from Composer
----------------------------

In your composer.json, in the require section, indicate:

```
"jelix/jcommunity-module": "1.1"
```

After a `composer update`:

1. if you use Jelix 1.6 or lower, you can declare jcommunity in the modulePath
   parameter of the configuration. Example:
    ```
    modulePath=(...);app:vendors/jelix/jcommunity-module/modules/
    ```
2. if you use Jelix 1.7 or higher, it is automatically declared.

About the jauth and jauthdb module
----------------------------------

You should do nothing about them. Uninstall and deactivate them. jCommunity provides its
own sql table, and its own dao. jCommunity provides all needed things, with some different
behaviors.

Using jCommunity with master_admin
----------------------------------

jCommunity 1.1+ can be used with the master_admin module. Continue the
installation by reading [the specific manual for this purpose](https://github.com/jelix/jcommunity-module/wiki/master_admin).

Setup
-----

In the configuration of the application, activate the module and the auth plugin
for the coordinator:

```
[modules]
jauth.access = 0
jauthdb.access = 0
jcommunity.access = 2
jcommunity.installparam =

[coordplugins]
auth=auth.coord.ini.php
```

Configure also parameters in the mailer section. jCommunity needs to send mail to users.

The installer supports some parameters. You should list them into the
`jcommunity.installparam`, with a semi colon as separator.

- `rewriteconfig`: indicate to change automatically the jauth configuration
- `defaultuser` : register an "admin" user (passowrd: "admin") into the community_users table
- `masteradmin` (1.1+): indicate that jcommunity is used for master_admin module.
  see [the dedicated chapter](master_admin)
- `notjcommunitytable` (1.1+): do not create the community_users table. It's up to you
  to do changes into your own SQL table. Not compatible with migratejauthdbusers
  and defaultuser
- `migratejauthdbusers` (1.1+): indicate to do migration  jlx_users records to community_users

ex:

```
jcommunity.installparam = "rewriteconfig;defaultuser"
```

Don't forget double quotes, else characters after ";" will be interpreted as a comment.


With jCommunity 1.1+, you can use jPref to allow to change some settings. If you
want to use it, you should also install the jpref module:

```
[modules]
jpref.access = 2
```

To finish the setup, launch the installer

```
php cmd.php installapp
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

Configuration
-------------

There are some settings that you can set in the configuration of your
application, or by jPref.

### settings in the main configuration (jCommunity 1.1+)

You should add a `jcommunity` section, and you can set these parameters:

- `loginResponse`: the alias of the response in the jcommunity controller
  to display the login form. By default: `html`.
- `registrationEnabled`: indicates if the registration feature is enabled
  (`on`) or not (`off`). By default: `on`
- `resetPasswordEnabled`: indicates if the reset password feature is enabled
  (`on`) or not (`off`). By default: `on`
- `disableJPref`: when `on`, indicates to not use jPref (see below) to store 
  "registrationEnabled" and "resetPasswordEnabled" preferences. By default: `off`.

ex:

```
[jcommunity]
loginresponse = html  ; htmlauth for master_admin
registrationEnabled = off
resetPasswordEnabled = on
```


### settings with jPref (jCommunity 1.1+)

Using jPref allows to the admin user to change some settings (registration
and reset password) directly from the jpref_admin interface. If jPref is activated,
settings `registrationEnabled` and `resetPasswordEnabled` in the
configuration are ignored.

You can disable the use of jPref with the option `disableJPref`. See above.



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


