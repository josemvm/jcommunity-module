#!/bin/bash
ROOTDIR="/jelixapp"
APPNAME="jcommunity"
APPDIR="$ROOTDIR/test"
VAGRANTDIR="$APPDIR/vagrant"
APPHOSTNAME="jcommunity.local"
APPHOSTNAME2=""

source $VAGRANTDIR/jelixapp/system.sh

resetJelixMysql $APPNAME root jelix

mysql -u root -pjelix -e "drop table if exists community_users;drop table if exists jmessenger;" $APPNAME;

resetJelixInstall $APPDIR

initapp $APPDIR

resetJelixTemp $APPDIR

