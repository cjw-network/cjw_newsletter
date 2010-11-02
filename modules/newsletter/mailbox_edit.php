<?php
/**
 * File mailbox_edit.php
 *
 * Add or edit mailboxes
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];
$templateFile = "design:newsletter/mailbox_edit.tpl";

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$http = eZHTTPTool::instance();

// create new CjwNewsletterMailbox object
$mailboxObject = new CjwNewsletterMailbox();

if ( isset( $Params[ 'MailboxId' ] ) )
{
    // if id > 0 => edit view | 0 = add view
    if ( $Params[ 'MailboxId' ] > 0 )
    {
        if ( is_object( $mailboxObject ) )
        {
            // fetch mailbox data to edit by id
            $mailboxObject = $mailboxObject->fetchMailboxDataForEdit( $Params[ 'MailboxId' ] );
        }
    }

    // set data from edit/add view
    if ( $http->hasPostVariable( 'edit' ) )
    {
        $mailboxData = array(
                                'email'                    => $http->postVariable( 'email' ),
                                'server'                   => $http->postVariable( 'server' ),
                                'port'                     => $http->postVariable( 'port' ),
                                'user_name'                     => $http->postVariable( 'user' ),
                                'password'                 => $http->postVariable( 'password' ),
                                'type'                     => $http->postVariable( 'type' ),
                                'is_activated'             => $http->postVariable( 'is_activated' ),
                                'is_ssl'                   => $http->postVariable( 'is_ssl' ),
                                'delete_mails_from_server' => $http->postVariable( 'delete_mails_from_server' )
                             );
    }

    // if PublishButton was pushed than store new data
    if ( $http->hasPostVariable( 'PublishButton' ) )
    {
        // save data
        $resultStoreData = $mailboxObject->storeMailboxData( $Params[ 'MailboxId' ], $mailboxData );

        // positiv return, redirect to maibox list
        if ( $resultStoreData )
        {
            $module->redirectTo( "/".$http->postVariable( 'redirect' ) );
        }
    }
    // Cancel
    elseif( $http->hasPostVariable( 'DiscardButton' ) )
    {
        $module->redirectTo( "/".$http->postVariable( 'redirect' ) );
    }
}

$tpl = templateInit();

$viewParameters = array( 'offset'     => 0,
                         'namefilter' => '' );

// variablen mit () in der url in viewparameter übernehmen
// z.B.  ../list/(offset)/4  setzt die viewparametervariable $offset = 3
$userParameters = $Params[ 'UserParameters' ];
$viewParameters = array_merge( $viewParameters, $userParameters );

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'mailbox', $mailboxObject );

$Result = array();

$Result[ 'content' ] = $tpl->fetch( $templateFile );
//$Result[ 'ui_context' ] = 'edit';
$Result['path'] = array( array( 'url'  => false,
                                'text' => ezi18n( 'cjw_newsletter', 'Newsletter' ) ),
                         array( 'url'  => false,
                                'text' => ezi18n( 'cjw_newsletter/mailbox_item_list', 'Mail accounts' ) ) );

?>
