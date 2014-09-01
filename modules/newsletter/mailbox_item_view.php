<?php
/**
 * File mailbox_item_view.php
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

// newsletter/mailbox_item_view/ $mailboxItemId => show details of message
// newsletter/mailbox_item_view/ $mailboxItemId ?GetRawMailContent => show raw message as text
// newsletter/mailbox_item_view/ $mailboxItemId ?DownloadRawMailContent => download raw message

$http = eZHTTPTool::instance();
$module = $Params['Module'];
$templateFile = 'design:newsletter/mailbox_item_view.tpl';

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$mailboxItemId = (int) $Params['MailboxItemId'];

$mailboxItemObject = CjwNewsletterMailboxItem::fetch( $mailboxItemId );

if( !is_object( $mailboxItemObject ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $http->hasVariable( 'GetRawMailContent' ) )
{
    header('Content-Type: text/plain');
    echo $mailboxItemObject->getRawMailMessageContent();
    eZExecution::cleanExit();
}
elseif ( $http->hasVariable( 'DownloadRawMailContent' ) )
{
    downloadFile( $mailboxItemObject->getFilePath() );
}
else
{
    $cjwNewsletterMailParserObject = new CjwNewsletterMailParser( $mailboxItemObject );

    if ( is_object( $cjwNewsletterMailParserObject ) )
    {
        $parseHeaderArray = $cjwNewsletterMailParserObject->parse();
    }

    $tpl = templateInit();

    $tpl->setVariable( 'mailbox_item', $mailboxItemObject );
    $tpl->setVariable( 'mailbox_item_raw_content', $mailboxItemObject->getRawMailMessageContent() );
    $tpl->setVariable( 'mailbox_header_hash', $parseHeaderArray );

    $Result = array();

    $Result['content'] = $tpl->fetch( $templateFile );
    $Result['path'] = array( array( 'url' => 'newsletter/mailbox_item_list',
                                    'text' => ezi18n( 'cjw_newsletter/mailbox_item_view', 'Mailbox item list' ) ),
                                array( 'url' => false,
                                    'text' => ezi18n( 'cjw_newsletter/mailbox_item_view', 'Mailbox item view' ) ) );
}


// helpfunction
/**
 * Passthrough file, and exit cleanly
*/
function downloadFile( $filePath )
{

    if( !file_exists( $filePath ) )
    {
        header("HTTP/1.1 404 Not Found");
        eZExecution::cleanExit();
    }

    ob_clean();

    header("Pragma: public");
    header("Expires: 0"); // set expiration time
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    header("Content-Disposition: attachment; filename=" . basename( $filePath ) );

    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".filesize( $filePath ));

    ob_end_clean();

    @readfile( $filePath );
    eZExecution::cleanExit();

}

?>
