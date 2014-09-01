<?php
/**
 * File user_remove.php
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = templateInit();

$templateFile = 'design:newsletter/user_remove.tpl';

$newsLetterUserId = (int) $Params['NewsletterUserId'];
$newsletterUserObject = CjwNewsletterUser::fetch( $newsLetterUserId );

if( !is_object( $newsletterUserObject ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$redirectUrlCancel = '/newsletter/user_view/'. $newsletterUserObject->attribute( 'id' );
if( $http->hasVariable( 'RedirectUrlActionCancel' ) )
{
    $redirectUrlCancel = $http->variable( 'RedirectUrlActionCancel' );
}

$redirectUrlRemove = '/newsletter/user_list/';
if( $http->hasVariable( 'RedirectUrlActionRemove' ) )
{
    $redirectUrlRemove = $http->variable( 'RedirectUrlActionRemove' );
}

// show an overview of all things we will be delete
/*
 - all subscriptions
 - user data
 - edition_send_items we should keep at the moment
 - may be give an option for blacklist user
 */


if ( $module->isCurrentAction( 'Remove' ) )
{
    // remove nl user object and all subscriptions
    $newsletterUserObject->remove();

    $module->redirectTo( $redirectUrlRemove );
}
elseif ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( $redirectUrlCancel );
}

$viewParameters = array();
if( is_array( $Params['UserParameters'] ) )
{
    $viewParameters = array_merge( $viewParameters, $Params['UserParameters'] );
}

$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'newsletter_user', $newsletterUserObject );
$tpl->setVariable( 'newsletter_user_id', $newsLetterUserId );
$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_remove', $redirectUrlRemove );

$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'cjw_newsletter/user_remove', 'Remove newsletter user' ) ) );

?>
