#!/bin/bash

# Dieses Script ruft die datei  'ezroot/extension/svnupdatemain.sh EXTENSIONNAME' auf
# um ein svn update auszulösen

CURRENT_DIR=`pwd`
EXTENSION=`basename $CURRENT_DIR`

cd ..
sh ./svnupdatemain.sh $EXTENSION
cd $EXTENSION