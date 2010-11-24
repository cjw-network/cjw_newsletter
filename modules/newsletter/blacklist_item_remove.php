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
$blackListItemArray = array();
$deleteIDArray = $http->hasVariable( 'BlacklistIDArray' ) ? $http->variable( 'BlacklistIDArray' ) : array();
$email = $http->hasVariable( 'Email' ) ? trim( $http->variable( 'Email' ) ) : '';

if ( $email )
{
    $itemByEmail = CjwNewsletterBlacklistItem::fetchByEmail( $email );
    if( !is_object( $itemByEmail ) )
    {
        eZDebug::writeError( "Given email ($email) isn't blacklisted", 'newsletter/blacklist_item_remove' );
        return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
    }
    $blackListItemArray[] = $itemByEmail;
}

if ( $deleteIDArray )
{
    foreach ( $deleteIDArray as $id )
    {
        $itemByID = CjwNewsletterBlacklistItem::fetch( $id );
        if( !is_object( $itemByID ) )
        {
            eZDebug::writeError( "Given id ($id) isn't blacklisted", 'newsletter/blacklist_item_remove' );
            return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
        }
        $blackListItemArray[] = $itemByID;
    }
}

foreach ( $blackListItemArray as $blackListItem )
{
    $blackListItem->remove();
}

if ( $http->hasVariable( 'RedirectURI' ) )
    $module->redirectTo( trim( $http->variable( 'RedirectURI' ) ) );
elseif ( $http->hasSessionVariable( 'LastAccessesURI' ) )
    $module->redirectTo( $http->sessionVariable( 'LastAccessesURI' ) );
else
    $module->redirectToView( 'blacklist_item_list' );

?>
