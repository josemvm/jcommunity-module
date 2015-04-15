;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

locale=fr_FR
charset=UTF-8

availableLocales = "fr_FR"

; see http://www.php.net/manual/en/timezones.php for supported values
timeZone="Europe/Paris"

pluginsPath=
modulesPath="lib:jelix-modules/,app:modules/,app:../modules/"

theme=default


[modules]
jelix.access=2
test.access=2
jcommunity.access=2
jmessenger.access=2
jcommunity.installparam=defaultuser


jacl2.access=0
jacl2db.access=0
jcommunity_phorum.access=0


jacldb.access=0
jauth.access=0
jauthdb.access=0
junittests.access=0
jWSDL.access=0

[coordplugins]
auth=auth.coord.ini.php

[responses]
html=testResponse


[error_handling]
messageLogFormat="%date%\t[%code%]\t%msg%\t%file%\t%line%\n"
errorMessage="Une erreur technique est survenue. Désolé pour ce désagrément."


[compilation]
checkCacheFiletime=on
force=off

[jResponseHtml]
plugins =debugbar

[urlengine]
; name of url engine :  "simple" or "significant"
engine=significant

; enable the parsing of the url. Set it to off if the url is already parsed by another program
; (like mod_rewrite in apache), if the rewrite of the url corresponds to a simple url, and if
; you use the significant engine. If you use the simple url engine, you can set to off.
enableParser=on

multiview=off

; basePath corresponds to the path to the base directory of your application.
; so if the url to access to your application is http://foo.com/aaa/bbb/www/index.php, you should
; set basePath = "/aaa/bbb/www/".
; if it is http://foo.com/index.php, set basePath="/"
; Jelix can guess the basePath, so you can keep basePath empty. But in the case where there are some
; entry points which are not in the same directory (ex: you have two entry point : http://foo.com/aaa/index.php
; and http://foo.com/aaa/bbb/other.php ), you MUST set the basePath (ex here, the higher entry point is index.php so
; : basePath="/aaa/" )
basePath=

; leave empty to have jelix error messages
notfoundAct=
;notfoundAct = "jelix~error:notfound"

; liste des actions requerant https (syntaxe expliquée dessous), pour le moteur d'url simple
simple_urlengine_https=


[simple_urlengine_entrypoints]
index="@classic"


[fileLogger]
default=messages.log


[mailer]
webmasterEmail="root@localhost"
webmasterName=
mailerType=file


[mailLogger]
email="root@localhost"
