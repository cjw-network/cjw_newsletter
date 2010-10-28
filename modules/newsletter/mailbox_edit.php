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

include_once( 'kernel/common/template.php' );

$http = eZHTTPTool::instance();

// create new CjwNewsletterMailbox object
$mailboxObject = new CjwNewsletterMailbox( true );

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
    if ( $http->variable( 'edit' ) )
    {
        $mailboxData = array(
                                'email'                    => $http->variable( 'email' ),
                                'server'                   => $http->variable( 'server' ),
                                'port'                     => $http->variable( 'port' ),
                                'user'                     => $http->variable( 'user' ),
                                'password'                 => $http->variable( 'password' ),
                                'type'                     => $http->variable( 'type' ),
                                'is_activated'             => $http->variable( 'is_activated' ),
                                'is_ssl'                   => $http->variable( 'is_ssl' ),
                                'delete_mails_from_server' => $http->variable( 'delete_mails_from_server' )
                             );
    }

    // if PublishButton was pushed than store new data
    if ( $http->variable( 'PublishButton' ) )
    {
        // save data
        $resultStoreData = $mailboxObject->storeMailboxData( $Params[ 'MailboxId' ], $mailboxData );

        // positiv return, redirect to maibox list
        if ( $resultStoreData )
        {
            $module->redirectTo( "/".$http->variable( 'redirect' ) );
        }
    }
    // Cancel
    elseif( $http->variable( 'DiscardButton' ) )
    {
        $module->redirectTo( "/".$http->variable( 'redirect' ) );
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