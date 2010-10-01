<?php
/**
 * File archive.php
 *
 * -get the stored content of newsletter edition<br>
 * -may be parse user content<br>
 * -newsletter / archive / $edition_send_hash<br>
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

include_once( 'kernel/common/template.php' );
$module = $Params["Module"];
$http = eZHTTPTool::instance();

$editionSendHash = $Params['EditionSendHash'];
$outputFormatId = 0;
$subscriptionHash = false;

if( $Params['OutputFormatId'] )
    $outputFormatId = (int) $Params['OutputFormatId'];

if( $Params['SubscriptionHash'] )
    $subscriptionHash = $Params['SubscriptionHash'];

$editionSendObject = CjwNewsletterEditionSend::fetchByHash( $editionSendHash );

if( !is_object( $editionSendObject  ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$newsletterDataArray = $editionSendObject->getParsedOutputXml();
$newsletterContent = false;

if( isset( $newsletterDataArray[ $outputFormatId ]) )
{
    $newsletterContentArray = $newsletterDataArray[ $outputFormatId ];
}

switch( $outputFormatId )
{
    // html
    case 0:
        $newsletterContent = $newsletterContentArray['body']['html'];
        break;
        // text
    case 1:

        $newsletterContent .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>newsletter - outputformat - text</title></head><body><pre>'. $newsletterContentArray['body']['text'] .'</pre></body></html>';

        break;
    default:
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$debug = 0;

if( $debug == 0 )
{
    header( "Content-type: text/html" );
    echo $newsletterContent;
    eZExecution::cleanExit();
}
else
{
    $Result = array();
    $Result['content'] = '<code>'.$newsletterContent.'</code>';
  //  header( "Content-type: text/html" );
}


?>