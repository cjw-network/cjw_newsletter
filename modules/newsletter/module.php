<?php
/**
 * File module.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$Module = array( "name" => "CJW Newsletter" );

$ViewList = array();

$ViewList['index'] = array(
    'script' => 'index.php',
    'functions' => array( 'index' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( ) );

$ViewList['settings'] = array(
    'script' => 'settings.php',
    'functions' => array( 'settings' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( ) );

$ViewList['mailbox_item_list'] = array(
    'script' => 'mailbox_item_list.php',
    'functions' => array( 'mailbox_item_list' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( )
    );

$ViewList['mailbox_list'] = array(
    'script' => 'mailbox_list.php',
    'functions' => array( 'mailbox_list' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( )
    );

$ViewList['mailbox_edit'] = array(
    'script' => 'mailbox_edit.php',
    'functions' => array( 'mailbox_edit' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'ui_context' => 'edit',
    'params' => array( 'MailboxId' )
    );

$ViewList['mailbox_item_view'] = array(
    'script' => 'mailbox_item_view.php',
    'functions' => array( 'mailbox_item_view' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'MailboxItemId' )
    );

$ViewList['blacklist_item_list'] = array(
    'script' => 'blacklist_item_list.php',
    'functions' => array( 'blacklist_item_list' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( )
    );

$ViewList['blacklist_item_add'] = array(
    'script' => 'blacklist_item_add.php',
    'functions' => array( 'blacklist_item_add' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'ui_context' => 'edit',
    'params' => array( )
    );

$ViewList['import_list'] = array(
    'script' => 'import_list.php',
    'functions' => array( 'import_list' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( )
    );

$ViewList['import_view'] = array(
    'script' => 'import_view.php',
    'functions' => array( 'import_view' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'ImportId' )
    );

$ViewList['user_list'] = array(
    'script' => 'user_list.php',
    'functions' => array( 'user_list' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( )
    );

$ViewList['user_view'] = array(
    'script' => 'user_view.php',
    'functions' => array( 'user_view' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'NewsletterUserId' ) );

$ViewList['user_remove'] = array(
    'script' => 'user_remove.php',
    'functions' => array( 'user_remove' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'NewsletterUserId' ),
    'single_post_actions' => array(
            'RemoveButton' => 'Remove',
            'CancelButton' => 'Cancel',
        )
     );

$ViewList['user_edit'] = array(
    'script' => 'user_edit.php',
    'functions' => array( 'user_edit' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'ui_context' => 'edit',
    'params' => array( 'NewsletterUserId' ),
    'single_post_actions' => array(
        'StoreButton' => 'Store',
        'StoreDraftButton' => 'StoreDraft',
        'CancelButton' => 'Cancel',
        )
    );

$ViewList['user_create'] = array(
    'script' => 'user_create.php',
    'functions' => array( 'user_create', 'user_edit' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'ui_context' => 'edit',
    'params' => array( ),
    'single_post_actions' => array(
        'CreateEditButton' => 'CreateEdit',
        'CancelButton' => 'Cancel',
        )
    );


$ViewList['subscription_list'] = array(
    'script' => 'subscription_list.php',
    'functions' => array( 'subscription_list' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'NodeId' ) );

$ViewList['subscription_view'] = array(
    'script' => 'subscription_view.php',
    'functions' => array( 'subscription_view' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'SubscriptionId' ) );

$ViewList['subscription_list_csvimport'] = array(
    'script' => 'subscription_list_csvimport.php',
    'functions' => array( 'subscription_list_csvimport' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'NodeId', 'ImportId' ) );

$ViewList['subscription_list_csvexport'] = array(
    'script' => 'subscription_list_csvexport.php',
    'functions' => array( 'subscription_list_csvexport' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'NodeId' ) );


$ViewList['subscribe'] = array(
    'script' => 'subscribe.php',
    'functions' => array( 'subscribe' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array(),
    'single_post_actions' => array(
        'SubscribeButton' => 'Subscribe'
    ),
    'post_action_parameters' => array(
          'Subscribe' => array( 'BackUrl' => 'BackUrlInput'  ) )
    );

$ViewList['subscribe_infomail'] = array(
    'script' => 'subscribe_infomail.php',
    'functions' => array( 'subscribe' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array(),
    'single_post_actions' => array(
        'SubscribeInfoMailButton' => 'SubscribeInfoMail'
    ),
    'post_action_parameters' => array(
          'SubscribeInfoMail' => array( 'Email' => 'EmailInput',
                                        'BackUrl' => 'BackUrlInput'  ) )
    );


$ViewList['configure'] = array(
    'script' => 'configure.php',
    'functions' => array( 'configure' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'UserHash', 'ConfirmAll' ),
    'single_post_actions' => array(
        'ConfirmButton' => 'Confirm'
    ),
//    'post_action_parameters' => array(
//        'Subscribe' => array( 'Test' => 'TestInput' ) )
    );

$ViewList['unsubscribe'] = array(
    'script' => 'unsubscribe.php',
    'default_navigation_part' => 'eznewsletternavigationpart',
    'functions' => array( 'unsubscribe' ),
    'params' => array( 'Hash' ),
    'single_post_actions' => array(
        'SubscribeButton' => 'Unsubscribe',
        'CancelButton' => 'Cancel'
    ),
    'post_action_parameters' => array(
          'Cancel' => array( 'CancelUri' => 'CancelUriInput'  ) )
    );

$ViewList['preview'] = array(
    'script' => 'preview.php',
    'functions' => array( 'preview' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'EditionContentObjectId', 'VersionId', 'OutputFormat', 'SiteAccess', 'SkinName' ) );

$ViewList['preview_archive'] = array(
    'script' => 'preview_archive.php',
    'functions' => array( 'preview' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'EditionSendId', 'OutputFormat', 'NewsletterUserId' ) );

$ViewList['send'] = array(
    'script' => 'send.php',
    'functions' => array( 'send' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    // 'params' => array( 'ContentObjectId', 'ContentObjectVersion' ),
    'params' => array( 'NodeId' ),
    'unordered_params' => array( ),
    'single_post_actions' => array(
        'SendNewsletterTestButton' => 'SendNewsletterTest',
        'SendNewsletterButton' => 'SendNewsletter'
    ),
    'post_action_parameters' => array(
          'SendNewsletterTest' => array( 'EmailReseiverTest' => 'EmailReseiverTestInput' ),
          'SendNewsletter' => array( 'SendOutConfirmation' => 'SendOutConfirmationInput' )
     ) );

$ViewList['send_abort'] = array(
    'script' => 'send_abort.php',
    'functions' => array( 'send' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'EditionSendId' ) );

$ViewList['archive'] = array(
    'script' => 'archive.php',
    'functions' => array( 'archive' ),
    'default_navigation_part' => 'eznewsletternavigationpart',
    'params' => array( 'EditionSendHash', 'OutputFormatId', 'SubscriptionHash' ) );

$FunctionList['subscribe'] = array();
$FunctionList['configure'] = array();
$FunctionList['unsubscribe'] = array();
$FunctionList['subscription_list_csvimport'] = array();
$FunctionList['subscription_list_csvimport_import'] = array();
$FunctionList['subscription_list_csvexport'] = array();
$FunctionList['subscription_list'] = array();
$FunctionList['subscription_view'] = array();
$FunctionList['user_list'] = array();
$FunctionList['user_view'] = array();
$FunctionList['user_remove'] = array();
$FunctionList['user_edit'] = array();
$FunctionList['user_create'] = array();
$FunctionList['preview'] = array();
$FunctionList['archive'] = array();
$FunctionList['index'] = array();
$FunctionList['settings'] = array();
$FunctionList['send'] = array();
$FunctionList['mailbox_item_list'] = array();
$FunctionList['mailbox_item_view'] = array();
$FunctionList['mailbox_list'] = array();
$FunctionList['mailbox_edit'] = array();
$FunctionList['blacklist_item_list'] = array();
$FunctionList['blacklist_item_add'] = array();
$FunctionList['import_list'] = array();
$FunctionList['import_view'] = array();

$FunctionList['admin'] = array(); // for display / hide of admin menue

?>