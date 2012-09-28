<?php
/**
 * File import_view.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

// newsletter/import_view/ $import_id => show details of an import


$http = eZHTTPTool::instance();
$module = $Params['Module'];
$templateFile = 'design:newsletter/import_view.tpl';

$importId = (int) $Params['ImportId'];
$importObject = CjwNewsletterImport::fetch( $importId );

$viewParameters = array( 'offset' => 0,
                         'namefilter' => '' );

$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( $viewParameters, $userParameters );

if( !is_object( $importObject ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if( $http->hasPostVariable( 'RemoveSubsciptionsByAdminButton' ) )
{
    $removeResult = $importObject->removeActiveSubscriptionsByAdmin();
}

$tpl = eZTemplate::factory();

$tpl->setVariable( 'import_object', $importObject );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );


$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezpI18n::tr( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => 'newsletter/import_list',
                                 'text' => ezpI18n::tr( 'cjw_newsletter/import_view', 'Imports' ) ),
                          array( 'url'  => false,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/import_view', 'Import details' ) ) );

?>
