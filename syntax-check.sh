#!/bin/sh

for i in `find . -name \*.php`; do php -l $i | grep -v "No syntax errors"; done

for i in `find . -name \*.php`; do
	cat $i | \
		sed -e 's/[[:space:]]while(/ while (/' | \
		sed -e 's/[[:space:]]if(/ if (/' | \
		sed -e 's/[[:space:]]else(/ else (/' | \
		sed -e 's/[[:space:]]elseif(/ elseif (/' | \
		sed -e 's/[[:space:]]catch(/ catch (/' | \
		sed -e 's/[[:space:]]foreach(/ foreach (/' | \
		sed -e 's/[[:space:]]switch(/ switch (/' > /tmp/temporary.php
	cp /tmp/temporary.php $i
done

echo "Checking for boolean/integer"
for i in `find . -name \*.php | grep trunk/src`; do cat $i | sed -e 's/@param boolean/@param bool/' > /tmp/temporary.php; cp /tmp/temporary.php $i; done
for i in `find . -name \*.php | grep trunk/src`; do cat $i | sed -e 's/@param integer/@param int/' > /tmp/temporary.php; cp /tmp/temporary.php $i; done
for i in `find . -name \*.php | grep trunk/src`; do cat $i | sed -e 's/@return boolean/@return bool/' > /tmp/temporary.php; cp /tmp/temporary.php $i; done
for i in `find . -name \*.php | grep trunk/src`; do cat $i | sed -e 's/@return integer/@return int/' > /tmp/temporary.php; cp /tmp/temporary.php $i; done

echo "Fixing return"
for i in `find . -name \*.php | grep trunk/src`; do cat $i | sed -e 's/@returns/@return/' > /tmp/temporary.php; cp /tmp/temporary.php $i; done

echo "Checking for wrong braces placement for functions"
grep -rn "function" * | grep "{" | grep -v "{@" | grep -v svn | grep "\.php:"
grep -rn "class" * | grep "{" | grep -v "{@" | grep -v svn | grep "\.php:"
grep -rn "interface" * | grep "{" | grep -v "{@" | grep -v svn | grep "\.php:"

echo "Checking for wrong if/else + brackets"
grep -rn "if" * | grep "{" | grep -v svn | grep "\.php:"
grep -rn "else" * | grep "{" | grep -v svn | grep "\.php:"


echo "Checking for wrong 'try' syntax':"
grep -nr "try" * | grep "[}{]" | grep -v svn-base | grep "\.php"

echo "Checking for wrong 'catch' syntax':"
grep -nr "catch" * | grep "[}{]" | grep -v svn-base | grep "\.php"

echo "Checking for wrong closing bracket:"
grep -nr "[^[:space:](]);" * | grep -v svn-base | grep -v tests | grep "\.php"

echo "Checking for wrong opening bracket:"
grep -nr "([^[:space:]C)]" * | grep -v svn-base | grep -v tests | grep -v "(string)" | grep -v "(int)" | grep -v "(float)" | grep -v "*" | grep "\.php:"

echo "Fixing double copyright and license tags:"
for i in `find . -name \*.php`; do
    php -r "\$f = file( '$i' ); \$o = fopen( '/tmp/temp.php', 'w' ); \$ca = \$la = 1; foreach( \$f as \$l ) { \$s = 1; if ( preg_match( '|@copyright|', \$l ) ) { if ( \$ca ) { \$ca--; } else { \$s = 0; } }; if ( preg_match( '|@license|', \$l ) ) { if ( \$la ) { \$la--; } else { \$s = 0; } }; if ( \$s) fwrite( \$o, \$l ); }"
    cp /tmp/temp.php $i
done

echo "Fixing comments:"
for i in `find . -name \*.php`; do
	php -r "\$f = file( '$i' ); \$o = fopen( '/tmp/temp.php', 'w' ); foreach( \$f as \$l ) { if ( preg_match( '@autogen@', \$l ) ) { fwrite( \$o, \$l ); } else { \$l = preg_replace( '@ \/\/([^ ])@', ' // \1', \$l ); fwrite( \$o, \$l ); } }"
    cp /tmp/temp.php $i
done

echo "Fixing end of line spaces:"
for i in `find . -name \*.php`; do cat $i | sed -e 's/[[:space:]]\+$//' > /tmp/temporary.php; cp /tmp/temporary.php $i; done

for i in `find . -name \*.php`; do php -l $i | grep -v "No syntax errors"; done
