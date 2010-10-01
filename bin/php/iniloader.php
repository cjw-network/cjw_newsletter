#!/usr/bin/env php
<?php

/**
 * File iniloader.php
 *
 * -script to get a serialized ini object of the siteaccess<br>
 * -iniloader.php -s siteaccess<br>
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @author Felix Woldt 2008
 * @subpackage phpscript
 * @filesource
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish INI Reader\n\n" .
                                                        "Read INI Files\n" .
                                                        "\n" .
                                                        "iniloader.php -s siteaccess site.ini" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();
$options = $script->getOptions( "",
                                "[ininame]",
                                array() );
$script->initialize();
$iniName = $options['arguments'][0];
$ini = eZINI::instance('site.ini');

//serialize( $ini );
$cli->output( serialize( $ini ) );

$script->shutdown();

?>
