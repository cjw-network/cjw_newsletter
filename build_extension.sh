#!/bin/sh
#
# TODO build extension from git repository


# erzeugt ein cjw_newsletter build package
# mit ant
# tar.gz package

EXTENSION_NAME=cjw_newsletter



CURRENT_DIR=`pwd`

# svn aktualisieren damit svnversion besseren output hat
cd ..

echo "svn update $CURRENT_DIR -r HEAD"
svn update $CURRENT_DIR -r HEAD

cd $CURRENT_DIR


echo '---------------------------------------------------'
echo "START Build:"  $EXTENSION_NAME
echo '---------------------------------------------------'

#SVN_REVISON=`svnversion`
# revision for package

# beta 2 is 13095
SVN_REVISON="13095"

echo "current svn_revision = $SVN_REVISON"
ant clean -Dextension.name=$EXTENSION_NAME
ant all -Dextension.name=$EXTENSION_NAME -Dsvn.revision=$SVN_REVISON
ant clean -Dextension.name=$EXTENSION_NAME
echo '---------------------------------------------------'
echo "END Build:"  $EXTENSION_NAME
echo '---------------------------------------------------'