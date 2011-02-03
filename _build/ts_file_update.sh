#!/bin/sh
#  erzeugt alle translationfiles  - updated diese gegebenfalls
# cd /ezroot/extension
# sh ts_file_udpade.sh

CURRENT_DIR = `pwd`
EXTENSION_NAME=cjw_newsletter

cd ..
cd ..
cd ..

echo '---------------------------------------------------'
echo "Creating untranslated ts-file for:"  $EXTENSION_NAME
echo '---------------------------------------------------'

./bin/linux/ezlupdate -e extension/$EXTENSION_NAME -u --utf8

echo '---------------------------------------------------'
echo "Creating/Update ts-file for"  $EXTENSION_NAME
echo '---------------------------------------------------'

./bin/linux/ezlupdate -e extension/$EXTENSION_NAME ger-DE --utf8
./bin/linux/ezlupdate -e extension/$EXTENSION_NAME eng-US --utf8
./bin/linux/ezlupdate -e extension/$EXTENSION_NAME fre-FR --utf8

#cd extension/$EXTENSION_NAME/
cd $CURRENT_DIR