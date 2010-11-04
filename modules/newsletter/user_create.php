<?php
/**
 * File user_create.php
 *
 * create a new newsletter user - if email exists redirect to user_edit of existing newsletter user
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */


/**
 * newsletter/user_create?Email=abc@examplcom
 * PostParameter for email
 */

// linked from
// - newsletter/user_list               ?RedirectUrl=
// - newsletter/subscription_list       ?RedirectUrl=

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = templateInit();

$templateFile = 'design:newsletter/user_create.tpl';

$contextCreateNewsletterUser = false;

$subscriptionDataArr = array( 'first_name' => '' ,
                                 'last_name' => '',
                                 'email' => '',
                                 'salutation' => '',
                                 'note' => '',
                                 'id_array' => array(),
                                 'list_array' => array(),
                                 'list_output_format_array' => array()
                                );

$warningArr = array();

$oldPostArray = array();

if( $http->hasPostVariable( 'OldPostVarSerialized' ) )
{
    $oldPostArray =  unserialize( base64_decode( $http->postVariable( 'OldPostVarSerialized' ) ) );
}
else
{
    $oldPostArray = $_POST;
}

$redirectUrlCancel = $redirectUrlStore = 'newsletter/user_list';

if ( $http->hasVariable( 'RedirectUrlActionCancel' ) )
{
    $redirectUrlCancel = $http->variable( 'RedirectUrlActionCancel' );
}
elseif ( $http->hasVariable( 'RedirectUrl' ) )
{
    $redirectUrlCancel = $http->variable( 'RedirectUrl' );
}

if ( $http->hasVariable( 'RedirectUrlActionStore' ) )
{
    $redirectUrlStore = $http->variable( 'RedirectUrlActionStore' );
}
elseif ( $http->hasVariable( 'RedirectUrl' ) )
{
    $redirectUrlStore = $http->variable( 'RedirectUrl' );
}


// set data from POST for new and existing users
if ( $http->hasPostVariable( 'Subscription_Email' ) )
{
    $subscriptionDataArr['email'] =  trim( $http->postVariable( 'Subscription_Email' ) );

}
if ( $http->hasPostVariable( 'Subscription_FirstName' ) )
{
    $subscriptionDataArr['first_name'] = trim( $http->postVariable( 'Subscription_FirstName' ) );
}
if ( $http->hasPostVariable( 'Subscription_LastName' ) )
{
    $subscriptionDataArr['last_name'] = trim( $http->postVariable( 'Subscription_LastName' ) );
}
if ( $http->hasPostVariable( 'Subscription_Salutation' ) )
{
    $subscriptionDataArr['salutation'] = trim( $http->postVariable( 'Subscription_Salutation' ) );
}
if ( $http->hasPostVariable( 'Subscription_Note' ) )
{
    $subscriptionDataArr['note'] = trim( $http->postVariable( 'Subscription_Note' ) );
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

// validate data if new user will be created
if ( $module->isCurrentAction( 'CreateEdit' ) )
{

    $newsletterUserId = -1;
    $msg = 'edit_new';

    $requiredSubscriptionFields = array( 'email' );
    foreach ( $requiredSubscriptionFields as $fieldName )
    {
        switch ( $fieldName )
        {
            case 'email':
            {
                if ( !eZMail::validate( $subscriptionDataArr['email'] ) || $subscriptionDataArr['email'] == '' )
                {
                    $warningArr['email'] = array( 'field_key'   => ezi18n( 'cjw_newsletter/subscription', 'Email'),
                                                  'message'     => ezi18n( 'cjw_newsletter/subscription', 'You must provide a valid email address.' ) );
                }
                else
                {
                    // check if email already exists
                    $existingNewsletterUserObject = CjwNewsletterUser::fetchByEmail( $subscriptionDataArr['email'] );

                    if( is_object( $existingNewsletterUserObject ) )
                    {
                        // If email exists redirect to user_edit
                        $newsletterUserId = $existingNewsletterUserObject->attribute( 'id' );
                        $msg = 'edit_existing';

                     /*   $warningArr['email'] = array( 'field_key'   => ezi18n( 'cjw_newsletter/subscription', 'Email' ),
                                                'message'     => ezi18n( 'cjw_newsletter/subscription', 'Email is already used by an other newsletter user.' ) );
                                                */
                    }

                }

            } break;
            default:
        }
    }

    // only store changes if all is ok
    if( $module->isCurrentAction( 'CreateEdit' ) && count( $warningArr ) == 0 )
    {
        // rerun with all postData
        $rerunUrl = 'newsletter/user_edit/'. $newsletterUserId;
        $newPostArray = array_merge( $oldPostArray, $_POST );
        if ( isset( $newPostArray['OldPostVarSerialized'] ) )
            unset( $newPostArray['OldPostVarSerialized'] );

        $_POST = array();
        $_POST = $newPostArray;
        $_POST['UserCreateMsg'] = $msg;
        $_POST['StoreDraftButton'] = 'storedraft';
        $Result['rerun_uri'] = $rerunUrl;

        return $module->setExitStatus( eZModule::STATUS_RERUN );
    }

}
elseif ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( $redirectUrlCancel );
}

$tpl->setVariable( 'old_post_var_serialized', base64_encode( serialize( $oldPostArray ) ) );

$tpl->setVariable( 'subscription_data_array', $subscriptionDataArr );

$tpl->setVariable( 'warning_array', $warningArr );


$tpl->setVariable( 'redirect_url_action_cancel', $redirectUrlCancel );
$tpl->setVariable( 'redirect_url_action_store', $redirectUrlStore );

$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezi18n( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => 'newsletter/user_list',
                                 'text' => ezi18n( 'cjw_newsletter/user_list', 'Users' ) ),
                          array( 'url'  => false,
                                 'text' => ezi18n( 'cjw_newsletter/user_create', 'Create' ) ) );


?>
