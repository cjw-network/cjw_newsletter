<?php
/**
 * File preview_archive.php
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

$module = $Params["Module"];
$http = eZHTTPTool::instance();

$editionSendId = (int) $Params['EditionSendId'];

$outputFormatId = 0;
$newsletterUserId = 0;

if( $Params['OutputFormat'] )
    $outputFormatId = (int) $Params['OutputFormat'];

if( $Params['NewsletterUserId'] )
    $newsletterUserId = $Params['NewsletterUserId'];


$editionSendObject = CjwNewsletterEditionSend::fetch( $editionSendId );

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

// html / text  - multipart/alternative
if( $outputFormatId === 0 )
{
    $newsletterContent .= $newsletterContentArray['body']['html'];
    $textContent = "<hr /><pre>" . $newsletterContentArray['body']['text'] . "</pre></body>";
    $newsletterContent = preg_replace( array('%</body>%'), array( $textContent ), $newsletterContent);
}
// plain/text
elseif( $outputFormatId === 1 )
{

    $newsletterContent .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>newsletter - outputformat - text</title></head><body><pre>'. $newsletterContentArray['body']['text'] .'</pre></body></html>';
}
else
{
    return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}


$mailSubjectLabel = ezpI18n::tr( 'cjw_newsletter/preview', 'Email subject' );

$subjectStyle = 'style="background-color:#dddddd;border-color: #cccccc;border-width: 0 0 1px 0;border-style: solid;color:#333333;"';

$mailSubject = '<body${1}><!-- email subject preview start --><table width="100%" cellpadding="5" cellspacing="0" border="0" bgcolor="#dddddd" class="newsletter-skin-preview-email-subject" '. $subjectStyle .'><tr><th width="1%" nowrap>' . $mailSubjectLabel . ':</th><td width="99%">' . $newsletterContentArray['subject'] . '</td></tr></table></span><!-- email subject preview end -->';

$newsletterContent = preg_replace( "%<body(.*)>%", $mailSubject, $newsletterContent );

/*$mailSubject = "<body><b>Email subject:</b> ". $newsletterContentArray['subject'] . "<br />";
$newsletterContent = preg_replace( array('%<body>%'), array( $mailSubject ), $newsletterContent);*/

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
