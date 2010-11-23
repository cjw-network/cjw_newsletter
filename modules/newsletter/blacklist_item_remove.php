<?php
/**
 * File blacklist_item_remove.php
 *
 * Removes a blacklist item
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];

require_once( 'kernel/common/i18n.php' );

$http = eZHTTPTool::instance();

if( $http->hasVariable( 'Email' ) )
{
    $email = trim( $http->variable( 'Email' ) );
}
else
{
    eZDebug::writeError( "Missing email parameter", 'newsletter/blacklist_item_remove' );
    return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$blackListItem = CjwNewsletterBlacklistItem::fetchByEmail( $email );

if( !is_object( $blackListItem ) )
{
    eZDebug::writeError( "Given email ($email) isn't blacklisted", 'newsletter/blacklist_item_remove' );
    return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

// fetch the matching user to perform the redirection after deleting
$newsletterUserObject = $blackListItem->attribute( 'newsletter_user_object' );

    // if AddButton was pushed than store new data
if ( $http->hasVariable( 'RemoveBlacklistEntryButton' ) )
{
    $blackListItem->remove();
}

if ( is_object( $newsletterUserObject ) )
{
    $module->redirectToView( 'user_view', array( $newsletterUserObject->attribute( 'id' ) ) );
}
else
{
    $module->redirectToView( 'blacklist_item_list' );
}
?>
