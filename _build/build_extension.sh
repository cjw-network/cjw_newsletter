#!/bin/sh
# create cjw_newsletter build package
# tar.gz package

EXTENSION_NAME=cjw_newsletter

CURRENT_DIR=`pwd`

cd ..
cd ..
cd ..


EZROOT=`pwd`



echo '---------------------------------------------------'
echo "START Build:"  $EXTENSION_NAME
echo '---------------------------------------------------'

cd $EZROOT

php "extension/cjw_extensiontools/bin/php/build.php" "-e extension/$EXTENSION_NAME"

echo '---------------------------------------------------'
echo "END Build:"  $EXTENSION_NAME
echo '---------------------------------------------------'

cd $CURRENT_DIR