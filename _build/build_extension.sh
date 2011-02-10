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


# CURRENT_TIMESTAMP=`date +%s`
# echo "$CURRENT_TIMESTAMP"


php ./extension/cjw_extensiontools/bin/php/build.php -d extension/$EXTENSION_NAME

echo '---------------------------------------------------'
echo "END Build:"  $EXTENSION_NAME
echo '---------------------------------------------------'

cd $CURRENT_DIR