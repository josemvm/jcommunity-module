#!/bin/bash
ROOTDIR="/jelixapp"
MYSQL_VERSION="5.7"
PHP_VERSION="5.6"
FPM_SOCK="php\\/php5.6-fpm.sock"
APPNAME="jcommunity"
APPDIR="$ROOTDIR/test"
VAGRANTDIR="$APPDIR/vagrant"
APPHOSTNAME="jcommunity.local"
APPHOSTNAME2=""

source $VAGRANTDIR/jelixapp/system.sh

initsystem

resetComposer $APPDIR

source $VAGRANTDIR/reset_app.sh

echo "Done."
