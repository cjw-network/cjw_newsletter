<?php
/**
 * File configure.php
 *
 * Handle list settings for a user by hash
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
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

$newsletterUser = CjwNewsletterUser::fetchByHash( $Params['UserHash'] );

if ( !$newsletterUser )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

// if user is blacklisted or removed do not show configure view
switch( $newsletterUser->attribute('status') )
{
    case CjwNewsletterUser::STATUS_BLACKLISTED :
    case CjwNewsletterUser::STATUS_REMOVED_ADMIN :
    case CjwNewsletterUser::STATUS_REMOVED_SELF :
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    break;
}

$confirmAll = false;

$user = false;
$currentUser = eZUser::currentUser();
if ( $currentUser->isLoggedIn() )
{
    $user = $currentUser;
}

// schauen ob alle offenen subscription confirmed werden sollen
if( $Params['ConfirmAll'] == 'confirm' )
    $confirmAll = true;

// wenn auf configure das 1.mal geklickt wurde
if( $newsletterUser->attribute('is_confirmed') == false )
{
    // alle offenen subscription des users setzen
    $confirmAllResult = $newsletterUser->confirmAll();
    $tpl->setVariable( 'confirm_all_result', $confirmAllResult );

    $newsletterUser = CjwNewsletterUser::fetchByHash( $Params['UserHash'] );
}

// configure form submitbutton
// store data
// - remove
// - subscribe
if ( $module->isCurrentAction( 'Confirm' ) )
{

    $subscriptionDataArray = array( 'first_name' => '' ,
                                 'last_name' => '',
                                 'email' => '',
                                 'salutation' => '',
                                 'id_array' => array(),
                                 'list_array' => array(),
                                 'list_output_format_array' => array()
                                );

    // email + userId aus formular ignorieren
    $subscriptionDataArray['email'] = $newsletterUser->attribute('email');
    $subscriptionDataArray['ez_user_id'] = $newsletterUser->attribute('ez_user_id');

    // form data
    if ( $http->hasPostVariable( 'Subscription_FirstName' ) )
        $subscriptionDataArray['first_name'] = trim( $http->postVariable( 'Subscription_FirstName' ) );
    if ( $http->hasPostVariable( 'Subscription_LastName' ) )
        $subscriptionDataArray['last_name'] = trim( $http->postVariable( 'Subscription_LastName' ) );

    if ( $http->hasPostVariable( 'Subscription_Salutation' ) )
        $subscriptionDataArray['salutation'] = trim( $http->postVariable( 'Subscription_Salutation' ) );

    if ( $http->hasPostVariable( 'Subscription_IdArray' ) )
        $subscriptionDataArray['id_array'] = $http->postVariable( 'Subscription_IdArray' );

    if ( $http->hasPostVariable( 'Subscription_ListArray' ) )
        $subscriptionDataArray['list_array'] = $http->postVariable( 'Subscription_ListArray' );

    // $subscriptionDataArr['list_output_format_array'] = array();

    foreach ( $subscriptionDataArray['id_array'] as $listId )
    {
        if ( $http->hasPostVariable( "Subscription_OutputFormatArray_$listId" ) )
        {
            $subscriptionDataArray['list_output_format_array'][ $listId ] = $http->postVariable( "Subscription_OutputFormatArray_$listId" );
        }
        else
        {
            $defaultOutputFormatId = 0;
            $subscriptionDataArray['list_output_format_array'][ $listId ] = array( $defaultOutputFormatId );
        }
    }

    // TODO
    // required fields

    // update subscribe/ remove supscripions
    $subscriptionResultArray = CjwNewsletterSubscription::createSubscriptionByArray( $subscriptionDataArray,
                                                                                     CjwNewsletterUser::STATUS_PENDING,
                                                                                     $subscribeOnlyMode = false,
                                                                                     $context = 'configure' );

    $tpl->setVariable( 'changes_saved', true );
}

$newsletterUser = CjwNewsletterUser::fetchByHash( $Params['UserHash'] );
$tpl->setVariable( 'newsletter_user', $newsletterUser );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:newsletter/configure.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'cjw_newsletter/configure', 'Configure newsletter settings' ) ) );


?>
