<?php
/**
 * File mailbox_list.php
 *
 * List all stored mailboxes.
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];
$templateFile = "design:newsletter/mailbox_list.tpl";

$mailboxObject = new CjwNewsletterMailbox( true );

$listMailboxesCount = 0;

// return array with mailbox objects
// TODO result check (is array or object etc )
if ( is_object( $mailboxObject ) )
{
    $listMailboxes = $mailboxObject->fetchAllMailboxes();
    $listMailboxesCount = count( $listMailboxes );
}

$tpl = eZTemplate::factory();

$viewParameters = array( 'offset' => 0,
                         'namefilter' => '',
                         'redirect_uri' => $module->currentRedirectionURI() );

$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( $viewParameters, $userParameters );

$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'mailbox_list', $listMailboxes );
$tpl->setVariable( 'mailbox_list_count', $listMailboxesCount );

$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] = array( array( 'url'  => 'newsletter/index',
                                'text' => ezpI18n::tr( 'cjw_newsletter', 'Newsletter' ) ),
                         array( 'url'  => false,
                                'text' => ezpI18n::tr( 'cjw_newsletter/mailbox_item_list', 'Mail accounts' ) ) );


?>
