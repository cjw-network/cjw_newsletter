<?php
/**
 * File user_edit.php
 *
 * edit a newsletter user
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */


/**
 * newsletter/user_edit/ $nlUserId
 *
 * create a nl user if id = 0
 * PostParameter for email and firstname, lastname, Salutation + subscription so we can subscribe directly
 */

// linked from
// - newsletter/user_view
// - newsletter/user_list               ?RedirectUrl=
// - newsletter/subscription_list       ?RedirectUrl=

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = templateInit();

$templateFile = 'design:newsletter/user_edit.tpl';
$newsletterUserId = (int) $Params['NewsletterUserId'];
$contextCreateNewsletterUser = false;

$subscriptionDataArr = array(    'first_name' => '' ,
                                 'last_name' => '',
                                 'email' => '',
                                 'salutation' => '',
                                 'note' => '',
                                 'id_array' => array(),
                                 'list_array' => array(),
                                 'list_output_format_array' => array()
                                );

$warningArr = array();
$messageFeedback = '';

$context = 'user_edit';

$redirectUrlCancel = false;
$redirectUrlStore = false;

// TODO check if current user has access to user_create

// newsletter/user_edit/-1  => create new user
if ( $newsletterUserId === -1 )
{
    $contextCreateNewsletterUser = true;
    $newsletterUserObject = CjwNewsletterUser::create(     $subscriptionDataArr['email'],
                                                           $subscriptionDataArr['salutation'],
                                                           $subscriptionDataArr['first_name'],
                                                           $subscriptionDataArr['last_name'],
                                                           false,
                                                           CjwNewsletterUser::STATUS_CONFIRMED,
                                                           $context );
}
else
{
    $contextCreateNewsletterUser = false;
    $newsletterUserObject = CjwNewsletterUser::fetch( $newsletterUserId );
}

if ( !is_object( $newsletterUserObject ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

// check if access came from user_create view
if( $http->hasVariable( 'UserCreateMsg' ) )
{
    $userCreateMsg = $http->variable( 'UserCreateMsg' );

    switch ( $userCreateMsg )
    {
        case 'edit_new':
            $messageFeedback = ezi18n( 'cjw_newsletter/user_edit', 'Creating new newsletter user' );
            break;
        case 'edit_existing':
            $messageFeedback =  ezi18n( 'cjw_newsletter/user_edit', 'Edit existing newsletter user' );
            break;
    }
}

if ( $http->hasVariable( 'RedirectUrlActionCancel' ) )
{
    $redirectUrlCancel = $http->variable( 'RedirectUrlActionCancel' );
}
elseif ( $http->hasVariable( 'RedirectUrl' ) )
{
    $redirectUrlCancel = $http->variable( 'RedirectUrl' );
}
elseif ( $contextCreateNewsletterUser === false )
{
    $redirectUrlCancel = '/newsletter/user_view/'.$newsletterUserObject->attribute( 'id' );
}


if ( $http->hasVariable( 'RedirectUrlActionStore' ) )
{
    $redirectUrlStore = $http->variable( 'RedirectUrlActionStore' );
}
elseif ( $http->hasVariable( 'RedirectUrl' ) )
{
    $redirectUrlStore = $http->variable( 'RedirectUrl' );
}
elseif ( $contextCreateNewsletterUser === false )
{
    $redirectUrlStore = '/newsletter/user_view/'.$newsletterUserObject->attribute( 'id' );
}

// set data from POST for new and existing users
if ( $http->hasPostVariable( 'Subscription_Email' ) )
{
    $subscriptionDataArr['email'] =  trim( $http->postVariable( 'Subscription_Email' ) );
    $newsletterUserObject->setAttribute( 'email', $subscriptionDataArr['email'] );
}
if ( $http->hasPostVariable( 'Subscription_FirstName' ) )
{
    $subscriptionDataArr['first_name'] = trim( $http->postVariable( 'Subscription_FirstName' ) );
    $newsletterUserObject->setAttribute( 'first_name', $subscriptionDataArr['first_name'] );
}
if ( $http->hasPostVariable( 'Subscription_LastName' ) )
{
    $subscriptionDataArr['last_name'] = trim( $http->postVariable( 'Subscription_LastName' ) );
    $newsletterUserObject->setAttribute( 'last_name', $subscriptionDataArr['last_name'] );
}
if ( $http->hasPostVariable( 'Subscription_Salutation' ) )
{
    $subscriptionDataArr['salutation'] = trim( $http->postVariable( 'Subscription_Salutation' ) );
    $newsletterUserObject->setAttribute( 'salutation', $subscriptionDataArr['salutation'] );
}
if ( $http->hasPostVariable( 'Subscription_Note' ) )
{
    $subscriptionDataArr['note'] = trim( $http->postVariable( 'Subscription_Note' ) );
    $newsletterUserObject->setAttribute( 'note', $subscriptionDataArr['note'] );
}
if ( $http->hasPostVariable( 'Subscription_IdArray' ) )
    $subscriptionDataArr['id_array'] = $http->postVariable( 'Subscription_IdArray' );
if ( $http->hasPostVariable( 'Subscription_ListArray' ) )
    $subscriptionDataArr['list_array'] = $http->postVariable( 'Subscription_ListArray' );

//   $subscriptionDataArr['list_output_format_array'] = array();

foreach ( $subscriptionDataArr['id_array'] as $listId )
{
    if ( $http->hasPostVariable( "Subscription_OutputFormatArray_$listId" ) )
        $subscriptionDataArr['list_output_format_array'][ $listId ] = $http->postVariable( "Subscription_OutputFormatArray_$listId" );
    else
    {
        $defaultOutputFormatId = 0;
        $subscriptionDataArr['list_output_format_array'][ $listId ] = array( $defaultOutputFormatId );
     }
}



$viewParameters = array();
if( is_array( $Params['UserParameters'] ) )
{
    $viewParameters = array_merge( $viewParameters, $Params['UserParameters'] );
}

$tpl->setVariable( 'view_parameters', $viewParameters );

$dryRun = true;
$newsletter_user_subscription_array = $newsletterUserObject->attribute( 'subscription_array' );

$userIsBlacklisted = false;

// if user is blacklisted
switch( $newsletterUserObject->attribute('status') )
{
    case CjwNewsletterUser::STATUS_BLACKLISTED :
        $userIsBlacklisted = true;
        $messageFeedback = ezi18n( 'cjw_newsletter/user_edit', 'Can not edit newsletter user because he is blacklisted' );
       // return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    break;
}

  // validate data if new user will be created
if ( $userIsBlacklisted === false
     && ( $module->isCurrentAction( 'Store' )
          || $module->isCurrentAction( 'StoreDraft' ) ) )
{
    $messageArray['email']      = array( 'field_key'   => ezi18n( 'cjw_newsletter/subscription', 'Email'),
                                          'message'     => ezi18n( 'cjw_newsletter/subscription', 'You must provide a valid email address.' ) );

    $requiredSubscriptionFields = array( 'email' );
    foreach ( $requiredSubscriptionFields as $fieldName )
    {
        switch ( $fieldName )
        {
            case 'email':
            {
                if ( !eZMail::validate( $subscriptionDataArr['email'] ) || $subscriptionDataArr['email'] == '' )
                {
                    $warningArr['email'] = $messageArray['email'];
                }
                else
                {
                    // check if email already exists
                    $existingNewsletterUserObject = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );

                    if( is_object( $existingNewsletterUserObject )
                        && (int) $existingNewsletterUserObject->attribute('id') != (int) $newsletterUserObject->attribute('id') )
                    {
                        $warningArr['email'] = array( 'field_key'   => ezi18n( 'cjw_newsletter/subscription', 'Email' ),
                                                'message'     => ezi18n( 'cjw_newsletter/subscription', 'Email is already used by an other newsletter user.' ) );
                    }
                }

            } break;
            default:
        }
    }

    // only store changes if all is ok
    if( $module->isCurrentAction( 'Store' ) && count( $warningArr ) == 0 )
    {
        // no changes to db => test run
        $dryRun = false;
        $newsletterUserObject->store();
    }

    $idArray = $subscriptionDataArr['id_array'];
    $listArray = $subscriptionDataArr['list_array'];
    $listOutputFormatArray = $subscriptionDataArr['list_output_format_array'];
    $existingNewsletterUserId = $newsletterUserObject->attribute('id');

    // Approve all subscriptions for the new user
    // Keep existing subscriptions for existing users as is
    if ( $contextCreateNewsletterUser === true )
    {
        foreach ( $listArray as $listId )
        {
            $outputFormatArray = $listOutputFormatArray[ $listId ];
            $status = CjwNewsletterSubscription::STATUS_APPROVED;
            $newsletter_user_subscription_array[ $listId ] = CjwNewsletterSubscription::createUpdateNewsletterSubscription(
                                                                                                    $listId,
                                                                                                    $existingNewsletterUserId,
                                                                                                    $outputFormatArray,
                                                                                                    $status,
                                                                                                    $dryRun,
                                                                                                    $context );
        }
    }

    $listRemoveArray =  array_diff( $idArray, $listArray );

    if( $dryRun === true )
    {
        // unsubscribe from list by admin dummy => no changes will be done to db only for storeDraft action
        foreach ( $listRemoveArray as $listId )
        {
            unset( $newsletter_user_subscription_array[ $listId ] );
        }
    }
    else
    {
        // unsubscribe from list by admin
        foreach ( $listRemoveArray as $listId )
        {
            $newsletter_user_subscription_array[ $listId ] = CjwNewsletterSubscription::removeSubscriptionByAdmin( $listId, $existingNewsletterUserId );
        }
    }
}

if ( $module->isCurrentAction( 'Store' ) && count( $warningArr ) == 0 )
{
    if ( $contextCreateNewsletterUser === true && $redirectUrlStore === false)
    {
        $newNewsletterUserObject = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );
        if( is_object( $newNewsletterUserObject ) )
            $redirectUrlStore = '/newsletter/user_view/'.$newNewsletterUserObject->attribute( 'id' );
    }
    elseif ( $redirectUrlStore === false )
    {
        $redirectUrlStore = '/newsletter/user_list/';
    }

    // if all is ok
    $module->redirectTo( $redirectUrlStore );
}
elseif ( $module->isCurrentAction( 'Cancel' ) )
{
    if ( $redirectUrlCancel === false )
    {
        $redirectUrlCancel = '/newsletter/user_list/';
    }
    $module->redirectTo( $redirectUrlCancel );
}

//$newsletterUserObject = CjwNewsletterUser::fetch( $newsletterUserId );


$tpl->setVariable( 'newsletter_user_subscription_array', $newsletter_user_subscription_array );
$tpl->setVariable( 'subscription_data_array', $subscriptionDataArr );
$tpl->setVariable( 'newsletter_user_id', $newsletterUserId );

$tpl->setVariable( 'warning_array', $warningArr );
$tpl->setVariable( 'message_feedback', $messageFeedback );

$tpl->setVariable( 'newsletter_user', $newsletterUserObject );
$tpl->setVariable( 'available_salutation_array', CjwNewsletterUser::getAvailableSalutationNameArrayFromIni() );

$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_store', $redirectUrlStore );


$Result = array();
//$Result[ 'ui_context' ] = 'edit';
$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] =  array( array( 'url'  => false,
                                 'text' => ezi18n( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => false,
                                 'text' => ezi18n( 'cjw_newsletter/user_list', 'Users' ) ),
                          array( 'url'  => false,
                                 'text' => $newsletterUserObject->attribute( 'name' ) )  );

?>
