<?php /* #?ini charset="utf-8"? */

/**
 * File containing the extendedattributefilter ini
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage ini
 * @filesource
 */

/*

#The name of the filter.
[CjwNewsletterListFilter]

#The name of the extension where the filtering code is defined.
ExtensionName=cjw_newsletter

#The name of the filter class.
ClassName=CjwNewsletterListFilter

#The name of the method which is called to generate the SQL parts.
MethodName=createSqlParts

#The file which should be included (extension/myextension will automatically be prepended).
FileName=extendedattributefilter/cjwnewsletterlistfilter.php


[CjwNewsletterEditionFilter]
ExtensionName=cjw_newsletter
ClassName=CjwNewsletterEditionFilter
MethodName=createSqlParts
FileName=extendedattributefilter/cjwnewslettereditionfilter.php

*/ ?>

