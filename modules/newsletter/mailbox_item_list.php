<?php
/**
 * File mailbox_item_list.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];
$templateFile = "design:newsletter/mailbox_item_list.tpl";

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$http = eZHTTPTool::instance();
$tpl = templateInit();

if( $http->hasVariable( 'ConnectMailboxButton' ) )
{
    $collectMailResult = CjwNewsletterMailbox::collectMailsFromActiveMailboxes();
    $tpl->setVariable( 'collect_mail_result', $collectMailResult );
}

if( $http->hasVariable( 'BounceMailItemButton' ) )
{
    $parseResultArray = CjwNewsletterMailbox::parseActiveMailboxItems();
    $tpl->setVariable( 'parse_result', $parseResultArray );
}


$http = eZHTTPTool::instance();
$db = eZDB::instance();

$viewParameters = array( 'offset' => 0,
                         'namefilter' => '' );

$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( $viewParameters, $userParameters );

$limit = 10;
$limitArray = array( 10, 10, 25, 50 );
$limitArrayKey = eZPreferences::value( 'admin_mailbox_item_list_limit' );

// get user limit preference
if ( isset( $limitArray[ $limitArrayKey ] ) )
{
    $limit =  $limitArray[ $limitArrayKey ];
}

$mailboxItemList = CjwNewsletterMailboxItem::fetchAllMailboxItems( $limit, $viewParameters[ 'offset' ] );
$mailboxItemListCount = CjwNewsletterMailboxItem::fetchAllMailboxItemsCount( );


$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'mailbox_item_list', $mailboxItemList );
$tpl->setVariable( 'mailbox_item_list_count', $mailboxItemListCount );

$tpl->setVariable( 'limit', $limit );


$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezi18n( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => false,
                                 'text' => ezi18n( 'cjw_newsletter/mailbox_item_list', 'Bounces' ) ) );

?>
