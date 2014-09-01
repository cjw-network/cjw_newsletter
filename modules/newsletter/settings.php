<?php
/**
 * File settings.php
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

include_once( 'kernel/common/template.php' );

$module = $Params["Module"];
$http = eZHTTPTool::instance();

$viewParameters = array();


$tpl = templateInit();
$tpl->setVariable( 'view_parameters', $viewParameters );

//http://admin.eldorado-templin.info.jac400.in-mv.com/settings/view/eldorado-templin_admin/cjw_newsletter.ini

$tpl->setVariable( 'current_siteaccess', $viewParameters );

//$tpl->setVariable( 'link_array', $data['result']);

//$tpl->setVariable( 'csv_data_not_ok', $invalidLinien );


$currentSiteAccess = $GLOBALS['eZCurrentAccess'];
$currentSiteAccessName = $currentSiteAccess['name'];

$redirectUri = "/settings/view/$currentSiteAccessName/cjw_newsletter.ini";
return $module->redirectTo( $redirectUri );

/*
$Result = array();
$Result['content'] = $tpl->fetch( "design:newsletter/index.tpl" );
$Result['path'] = array( array( 'url' => false,
                                    'text' => 'newsletter' ),
                             array( 'url' => false,
                                    'text' => 'index' ) );
*/
?>