#!/bin/bash
ROOTDIR="/jelixapp"
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
