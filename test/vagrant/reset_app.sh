#!/bin/bash
ROOTDIR="/jelixapp"
APPNAME="jcommunity"
APPDIR="$ROOTDIR/test"
VAGRANTDIR="$APPDIR/vagrant"
APPHOSTNAME="jcommunity.local"
APPHOSTNAME2=""

source $VAGRANTDIR/jelixapp/system.sh

resetJelixMysql $APPNAME root jelix
resetJelixInstall $APPDIR

initapp $APPDIR

resetJelixTemp $APPDIR

