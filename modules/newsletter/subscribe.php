<?php
/**
 * File subscribe.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();

// ezuser, anonym or per hash

$templateFile = "design:newsletter/subscribe.tpl";
$warningArr = array();

$firstname = '';
$name = '';
$email = '';
$subscriptionDataArr = array( 'salutation' => -1,
                              'first_name' => '' ,
                              'last_name' => '',
                              'email' => '',
                              'id_array' => array(),
                              'list_array' => array(),
                              'list_output_format_array' => array(),
                                 // 'output_format_html_array' => array()
                                  );

$user = false;
$currentUser = eZUser::currentUser();
if ( $currentUser->isLoggedIn() )
{
    $user = $currentUser;
}

$subscriptionListArray = array();

if ( $module->isCurrentAction( 'Subscribe' ) )
{

    $backUrl = '/';
    if ( $module->hasActionParameter( 'BackUrl' ) )
    {
        $backUrl = $module->actionParameter( 'BackUrl' );
    }

    $postedEmail = trim( $http->postVariable( 'Subscription_Email' ) );
    $postedEmailIsFromLoggedInUser = false;
    // ez user
    if ( $user )
    {
        // - check if posted email address == email of current logged in user
        // yes => we do not need to send out an informationmail, because we know that the
        //        email is valid
        //        + store user_id
        // no  => create new nl_user with posted email, send subscription_confirmation email

        $currentUserEmail = $user->attribute('email');
        $currentEzUserId = $user->attribute('contentobject_id');

        if( strtolower( $currentUserEmail ) == strtolower( $postedEmail ) )
        {
            $postedEmailIsFromLoggedInUser = true;
            $subscriptionDataArr['ez_user_id'] = $currentEzUserId;
        }
    }
    // anonymous
    else
    {

    }
    $subscriptionDataArr['email'] = $postedEmail;

    if ( $http->hasPostVariable( 'Subscription_FirstName' ) )
        $subscriptionDataArr['first_name'] = trim( $http->postVariable( 'Subscription_FirstName' ) );
    if ( $http->hasPostVariable( 'Subscription_LastName' ) )
        $subscriptionDataArr['last_name'] = trim( $http->postVariable( 'Subscription_LastName' ) );
    if ( $http->hasPostVariable( 'Subscription_Salutation' ) )
        $subscriptionDataArr['salutation'] = trim( $http->postVariable( 'Subscription_Salutation' ) );
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

    $messageArray['list_array'] = array( 'field_key'   => ezpI18n::tr( 'cjw_newsletter/subscription', 'Newsletter'),
                                         'message'     => ezpI18n::tr( 'cjw_newsletter/subscription', 'You must choose a list for subscription.' ) );
    $messageArray['first_name'] = array( 'field_key'   => ezpI18n::tr( 'cjw_newsletter/subscription', 'First name'),
                                         'message'     => ezpI18n::tr( 'cjw_newsletter/subscription', 'You must enter a first name.' ) );
    $messageArray['last_name']  = array( 'field_key'   => ezpI18n::tr( 'cjw_newsletter/subscription', 'Last name'),
                                         'message'     => ezpI18n::tr( 'cjw_newsletter/subscription', 'You must enter a last name.' ) );
    $messageArray['email']      = array( 'field_key'   => ezpI18n::tr( 'cjw_newsletter/subscription', 'Email'),
                                         'message'     => ezpI18n::tr( 'cjw_newsletter/subscription', 'You must provide a valid email address.' ) );

    $requiredSubscriptionFields = array( 'list_array', 'email' );
    foreach ( $requiredSubscriptionFields as $fieldName )
    {
        switch ( $fieldName )
        {
            case 'list_array':
            {
                if ( count( $subscriptionDataArr['list_array'] ) == 0 )
                {
                    $warningArr['list_array'] = $messageArray['list_array'];
                }
            } break;
            case 'first_name':
            {
                if ( !$subscriptionDataArr['first_name'] )
                {
                    $warningArr['first_name'] = $messageArray['first_name'];
                }
            } break;
            case 'last_name':
            {
                if ( !$subscriptionDataArr['name'] )
                {
                    $warningArr['last_name'] = $messageArray['last_name'];
                }
            } break;
            case 'email':
            {
                if ( !eZMail::validate( $subscriptionDataArr['email'] ) )
                {
                    $warningArr['email'] = $messageArray['email'];
                }
            } break;
            default:
        }
    }

    // check if email already exists
    $existingNewsletterUserObject = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );

    $context = 'subscribe';

    // if current user is ez user and posted email equals ez_user_email don't send an email
    // only subscribe him
    if( $postedEmailIsFromLoggedInUser === true )
    {
        // all is ok -> send confirmation email
        if ( count( $warningArr ) == 0 )
        {
            // subscribe to all selected lists
            $subscriptionResultArray = CjwNewsletterSubscription::createSubscriptionByArray( $subscriptionDataArr,
                                                                                             CjwNewsletterUser::STATUS_PENDING,
                                                                                             true,
                                                                                             $context );
            $newNewsletterUser = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );

            $tpl->setVariable( 'user_email_already_exists', false );
            $tpl->setVariable( 'mail_send_result', false );
            $tpl->setVariable( 'newsletter_user', $newNewsletterUser );

            $tpl->setVariable( 'subscription_result_array', $subscriptionResultArray );
            $tpl->setVariable( 'back_url_input', $backUrl );

            $templateFile = 'design:newsletter/subscribe_success_ez_user.tpl';
        }
    }
    else
    {
        // subscription for anonymous users

        // email exists but subscription for email is done again
        // => email send with configure link
        if ( is_object( $existingNewsletterUserObject) )
        {
            $tpl->setVariable( 'user_email_already_exists', $subscriptionDataArr['email'] );

            // $existingNewsletterUserObject->sendSubriptionInfoMail();
            $mailSendResult = $existingNewsletterUserObject->sendSubcriptionInformationMail();

            $tpl->setVariable( 'newsletter_user', $existingNewsletterUserObject );
            $tpl->setVariable( 'mail_send_result', $mailSendResult );
            $tpl->setVariable( 'subscription_result_array', false );
            $tpl->setVariable( 'back_url_input', $backUrl );

            if ( $mailSendResult['send_result'] === true )
            {
                $templateFile = "design:newsletter/subscribe_success.tpl";
            }
            // errors
            else
            {
                $templateFile = "design:newsletter/subscribe_success_not.tpl";
            }
        }
        // all is ok -> send confirmation email
        else if ( count( $warningArr ) == 0 )
        {
            // subscribe to all selected lists
            $subscriptionResultArray = CjwNewsletterSubscription::createSubscriptionByArray( $subscriptionDataArr,
                                                                                             CjwNewsletterUser::STATUS_PENDING,
                                                                                             true,
                                                                                             $context );

            $newNewsletterUser = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );
            $mailSendResult = $newNewsletterUser->sendSubcriptionConfirmationMail();

            $tpl->setVariable( 'user_email_already_exists', false );
            $tpl->setVariable( 'mail_send_result', $mailSendResult );
            $tpl->setVariable( 'newsletter_user', $newNewsletterUser );

            $tpl->setVariable( 'subscription_result_array', $subscriptionResultArray );
            $tpl->setVariable( 'back_url_input', $backUrl );

            $templateFile = 'design:newsletter/subscribe_success.tpl';
        }
    }
}


if ( $user )
{
    $tpl->setVariable( 'user', $user );
}

if( isSet( $existingNewsletterUserObject ))
{
    $tpl->setVariable( 'newsletter_user', $existingNewsletterUserObject );
}

$tpl->setVariable( 'subscription_data_array', $subscriptionDataArr );

$tpl->setVariable( 'warning_array', $warningArr );

$salutationArray = CjwNewsletterUser::getAvailableSalutationNameArrayFromIni();

$tpl->setVariable( 'available_salutation_array', $salutationArray );
// for backwardcompatibility
$tpl->setVariable( 'available_saluation_array', $salutationArray );


$Result = array();
//$Result['content'] = $tpl->fetch( "design:newsletter/subscribe.tpl" );

$Result['content'] = $tpl->fetch( $templateFile );

$Result['path'] =  array( array( 'url'  => false,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => false,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/subscribe', 'Subscription form' ) ) );
?>
