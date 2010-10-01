<?php
/**
 * File user_view.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

include_once( 'kernel/common/template.php' );

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = templateInit();

$templateFile = 'design:newsletter/user_view.tpl';

$newsLetterUserId = (int) $Params['NewsletterUserId'];
$newsletterUserObject = CjwNewsletterUser::fetch( $newsLetterUserId );

if( !is_object( $newsletterUserObject ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$viewParameters = array();
if( is_array( $Params['UserParameters'] ) )
{
    $viewParameters = array_merge( $viewParameters, $Params['UserParameters'] );
}

// Parameter der View als Array dem Template übergeben
$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'newsletter_user', $newsletterUserObject );

$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezi18n( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => 'newsletter/user_list',
                                 'text' => ezi18n( 'cjw_newsletter/user_list', 'Users' ) ),
                          array( 'url'  => false,
                                 'text' => $newsletterUserObject->attribute('name') ) );

?>