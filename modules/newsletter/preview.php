<?php
/**
 * File preview.php
 *
 * Create a preview - return text or html<br>
 * -preview/ object_id / version_id/ outputformat/ siteaccess
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$http = eZHTTPTool::instance();
$module = $Params["Module"];

$editionContentObjectId = $Params['EditionContentObjectId'];
$versionId = $Params['VersionId'];
$outputFormat = $Params['OutputFormat'];
$siteAccess = $Params['SiteAccess'];
$skinName = $Params['SkinName'];


// show mailcontent inline to get debug messages
if( $http->hasVariable( 'ShowXmlTemplate' ) )
{
    $showXmlTemplateValue = (int) $http->variable( 'ShowXmlTemplate' );

    if ( $showXmlTemplateValue == 1    // ?ShowXmlTemplate=1   => output preview of xml stored in db after send out this newsletter edition
        || $showXmlTemplateValue == 2 // ?ShowXmlTemplate=2   => for debugging only
       )
    {
         $objectVersion = eZContentObjectVersion::fetchVersion( $versionId, $editionContentObjectId, true );

         if ( is_object( $objectVersion ) )
         {
             $editionDataMap = $objectVersion->attribute('data_map');
             $attributeEdition = $editionDataMap['newsletter_edition'];
             $attributeEditionContent = $attributeEdition->attribute('content');

             $outputXml = $attributeEditionContent->createOutputXml();

             if ( $showXmlTemplateValue == 2 )
             {
                 header( "Content-type: text/html; charset=utf-8" );
                 echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>newsletter - outputformat - text</title></head><body><pre>'. $outputXml .'</pre></body></html>';
                 return eZExecution::cleanExit();
             }
             else
             {
                 header( "Content-type: text/xml; charset=utf-8" );
                 echo $outputXml;
                 return eZExecution::cleanExit();
             }
         }
    }
}
else
{

$showRawContent = false;
// disable image include - so we have a good html preview
$forceSettingImageIncludeTo = 0;
$debug = false;

// ?ForceSettingImageIncludeTo=1 force image include
if( $http->hasVariable( 'ForceSettingImageIncludeTo' ) )
{
    $forceSetting = $http->variable( 'ForceSettingImageIncludeTo' );
    if ( $forceSetting === '0' )
    {
        $forceSettingImageIncludeTo = 0;
    }
    elseif ( $forceSetting === '1' )
    {
        $forceSettingImageIncludeTo = 1;
    }
    elseif ( $forceSetting === '-1' )
    {
        $forceSettingImageIncludeTo = -1;
    }
}

if( $http->hasVariable( 'ShowRawContent' ) && (int) $http->variable( 'ShowRawContent' ) == 1 )
{
    $showRawContent = true;
}

// show mailcontent inline to get debug messages
if( $http->hasVariable( 'Debug' ) && (int) $http->variable( 'Debug' ) == 1 )
{
    $debug = true;
}

$newsletterContent = '';
$newsletterContentArray = CjwNewsletterEdition::getOutput( $editionContentObjectId, $versionId, $outputFormat, $siteAccess, $skinName, $forceSettingImageIncludeTo );

if( $newsletterContentArray['content_type'] == 'text/html' )
{
    $newsletterContent .= $newsletterContentArray['body']['html'];

}
elseif( $newsletterContentArray['content_type'] == 'multipart/alternative' )
{
    $newsletterContent .= $newsletterContentArray['body']['html'];
    if( $showRawContent === false )
    {
        $textContent = "<hr /><pre>" . $newsletterContentArray['body']['text'] . "</pre></body>";
        $newsletterContent = str_replace( '</body>', $textContent, $newsletterContent );
    }
}
elseif( $newsletterContentArray['content_type'] == 'text/plain' )
{
    if( $showRawContent === false )
    {
        $newsletterContent .= '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>newsletter - outputformat - text</title></head><body>
<pre>'. $newsletterContentArray['body']['text'] .'</pre></body></html>';
    }
    else
    {
         $newsletterContent = $newsletterContentArray['body']['text'];
    }
}
else
{
    return $module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}
// debug parse preview PreviewEmbedImages
// TODO used in cronjob process, too
/*if( $previewEmbedImages )
{
    $eZRoot = $newsletterContentArray[ 'ez_root' ];
    $eZFile = $newsletterContentArray[ 'ez_file' ];

    $newsletterContent = str_replace( "src=\"$eZRoot", "src=\"$eZFile", $newsletterContent );
}
*/
//
// embed ends
//



if( $showRawContent === false )
{
    // insert email subject in preview after the body tag
    //$mailSubject = '<body${1}><!-- email subject preview start --><span class="newsletter-skin-preview-email-subject"><table <span class="newsletter-skin-preview-email-subject-label"><b>Email subject:</b></span> <span class="newsletter-skin-preview-email-subject-content">'. $newsletterContentArray['subject'] . '</span><br /></span><!-- email subject preview end -->';

    $mailSubjectLabel = ezpI18n::tr( 'cjw_newsletter/preview', 'Email subject' );

    $subjectStyle = 'style="background-color:#dddddd;border-color: #cccccc;border-width: 0 0 1px 0;border-style: solid;color:#333333;"';

    $mailSubject = '<body${1}><!-- email subject preview start --><table width="100%" cellpadding="5" cellspacing="0" border="0" bgcolor="#dddddd" class="newsletter-skin-preview-email-subject" '. $subjectStyle .'><tr><th width="1%" nowrap>' . $mailSubjectLabel . ':</th><td width="99%">' . $newsletterContentArray['subject'] . '</td></tr></table></span><!-- email subject preview end -->';

    $newsletterContent = preg_replace( "%<body(.*)>%", $mailSubject, $newsletterContent );
}

//unset( $newsletterContentArray['body'] );
//print_r( $newsletterContentArray );

if( $debug === true )
{
    $Result = array();
    $Result['content'] = '<code>'.$newsletterContent.'</code>';
    // header( "Content-type: text/html" );
}
else
{
    header( "Content-type: text/html; charset=utf-8" );
    echo $newsletterContent;
    eZExecution::cleanExit();
}

}

?>