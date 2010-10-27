<?php
/**
 * File index.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

include_once( 'kernel/common/template.php' );

$module = $Params[ 'Module' ];
$http = eZHTTPTool::instance();

$viewParameters = array( 'offset' => 0,
                         'namefilter' => '' );

$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( $viewParameters, $userParameters );

$tpl = templateInit();
$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'current_siteaccess', $viewParameters );
$Result = array();
$Result['content'] = $tpl->fetch( "design:newsletter/index.tpl" );
$Result['path'] = array( array( 'url'  => false,
                                'text' => ezpI18n::tr( 'cjw_newsletter', 'Newsletter' ) ),
                         array( 'url'  => false,
                                'text' => ezpI18n::tr( 'cjw_newsletter/index', 'Dashboard' ) ) );

?>
