<?php
/**
 * File containing the CjwNewsletterEdition class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Data management datatyp cjwnewsletteredition
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterEdition extends eZPersistentObject
{

    const STATUS_DRAFT = 'draft';
    const STATUS_PROCESS = 'process';  // sending
    const STATUS_ARCHIVE = 'archive';  // archived
    const STATUS_ABORT = 'abort';      // aborted

    /**
     * Initializes a new GeoadressData alias
     *
     * @param unknown_type $row
     * @return void
     */
    function CjwNewsletterEdition( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /**
     * @return void
     */
    static function definition()
    {
        return array( 'fields' => array( 'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_attribute_version' => array( 'name' => 'ContentObjectAttributeVersion',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_id' => array( 'name' => 'ContentObjectId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentclass_id' => array( 'name' => 'ContentClassId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         /*
                                         'status' => array( 'name' => 'Status',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         */

                                                                    ),
                      'keys' => array( 'contentobject_attribute_id', 'contentobject_attribute_version' ),
                      'function_attributes' => array( 'list_attribute_content' => 'getListAttributeContent',
                                                      'edition_send_array' => 'getEditionSendArray',
                                                      'edition_send_current' => 'getEditionSendCurrent',
                                                      'is_draft' => 'isDraft',
                                                      'is_process' => 'isProcess',
                                                      'is_archive' => 'isArchive',
                                                      'is_abort' => 'isAbort',
                                                      'status' => 'getStatus' ),
                      'class_name' => 'CjwNewsletterEdition',
                      'name' => 'cjwnl_edition' );
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZPersistentObject#attribute($attr, $noFunction)
     */
    function attribute( $attr, $noFunction = false )
    {
        switch ( $attr )
        {
            case 'list_attribute_content':
            {
                return $this->getListAttributeContent();
            } break;

            default:
                return eZPersistentObject::attribute( $attr );
        }
    }

    /**
     * Used in datatype cjwnewsletter_list
     *
     * @param unknown_type $attributeId
     * @param unknown_type $version
     * @return CjwNewsletterEdition
     */
    static function fetch( $attributeId, $version )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterEdition::definition(),
                        null,
                        array( 'contentobject_attribute_id' => $attributeId,
                               'contentobject_attribute_version' => $version  ),
                        null,
                        null,
                        true
                        );

        if ( count( $objectList ) > 0 )
            return $objectList[0];
    }

    /**
     * Returns current newsletter status
     *
     * @return status
     */
    function getStatus()
    {
        if ( $this->isProcess() )
            return CjwNewsletterEdition::STATUS_PROCESS;

        if ( $this->isArchive() )
            return CjwNewsletterEdition::STATUS_ARCHIVE;

        if ( $this->isAbort() )
            return CjwNewsletterEdition::STATUS_ABORT;

        if ( $this->isDraft() )
            return CjwNewsletterEdition::STATUS_DRAFT;

        return -1;
    }

    /**
     *
     * @return status
     */
    function isDraft()
    {
        return $this->getEditionStatus( CjwNewsletterEdition::STATUS_DRAFT );
    }

    /**
     *
     * @return status
     */
    function isProcess()
    {
        return $this->getEditionStatus( CjwNewsletterEdition::STATUS_PROCESS );
    }

    /**
     *
     * @return status
     */
    function isArchive()
    {
        return $this->getEditionStatus( CjwNewsletterEdition::STATUS_ARCHIVE );
    }

    /**
     *
     * @return status
     */
    function isAbort()
    {
         return $this->getEditionStatus( CjwNewsletterEdition::STATUS_ABORT );
    }

    /*
        welchen status hat die edition mit contentobject_id
        DRAFT
        PROCESS
        ARCHIVE
        ABORT
    */
    /**
     * Which status has the edition with the contentobject_id
     * (DRAFT, PROCESS, ARCHIVE, ABORT)
     *
     * @param unknown_type $virtualStatus
     * @return boolean
     */
    function getEditionStatus( $virtualStatus )
    {
        $editionContentObjectId = $this->attribute('contentobject_id');
        $editionContentObjectIdVersion = $this->attribute('contentobject_attribute_version');

        switch ( $virtualStatus )
        {
            /*
             * Ignore versions!
             *
             * Only one object should be has the status PROCESS
             */
            case CjwNewsletterEdition::STATUS_PROCESS :
            {
                 // if is a object version in sendprocess, it's can be WAIT_FOR_PROCESS or PROCESS
                $processResult = CjwNewsletterEditionSend::fetchByEditionContentObjectIdAndStatus(
                                            $editionContentObjectId,
                                            array( CjwNewsletterEditionSend::STATUS_WAIT_FOR_PROCESS,
                                                   CjwNewsletterEditionSend::STATUS_MAILQUEUE_CREATED,
                                                   CjwNewsletterEditionSend::STATUS_MAILQUEUE_PROCESS_STARTED  ),
                                            false
                                                   );

                if ( count( $processResult ) > 0 )
                    return true;

            } break;

            /*
             *START
             *as to current version
            */
            case CjwNewsletterEdition::STATUS_DRAFT :
            {
                // if hasn't the current version a entry in edition_send => DRAFT
                $processResult = CjwNewsletterEditionSend::fetchByEditionContentObjectIdVersion(
                                            $editionContentObjectId,
                                            $editionContentObjectIdVersion,
                                            true
                                            );

                if ( count( $processResult ) == 0 )
                    return true;

            } break;
            case CjwNewsletterEdition::STATUS_ARCHIVE :
            {
                // if sending of current version successful => ARCHIVE
                /*
                $processResult = CjwNewsletterEditionSend::fetchByEditionContentObjectIdVersionAndStatus(
                                            $editionContentObjectId,
                                            $editionContentObjectIdVersion,
                                            array( CjwNewsletterEditionSend::STATUS_MAILQUEUE_PROCESS_FINISHED ),
                                            false
                                            );
                */

                // only one objectversion should be has the status ARCHIVE
                $processResult = CjwNewsletterEditionSend::fetchByEditionContentObjectIdAndStatus(
                                            $editionContentObjectId,
                                            array( CjwNewsletterEditionSend::STATUS_MAILQUEUE_PROCESS_FINISHED ),
                                            false
                                                   );

                if ( count( $processResult ) > 0 )
                    return true;

            } break;
            case CjwNewsletterEdition::STATUS_ABORT :
            {

                /*
                 * If is aborted the current version => ABORT
                 * If sending of current version successful => ARCHIVE
                 */
                /*
                $processResult = CjwNewsletterEditionSend::fetchByEditionContentObjectIdVersionAndStatus(
                                            $editionContentObjectId,
                                            $editionContentObjectIdVersion,
                                            array( CjwNewsletterEditionSend::STATUS_ABORT ),
                                            false
                                            );
                 */

                // only one objectversion should be has the status ABORT
                $processResult = CjwNewsletterEditionSend::fetchByEditionContentObjectIdAndStatus(
                                        $editionContentObjectId,
                                        array( CjwNewsletterEditionSend::STATUS_ABORT ),
                                        false
                                        );

                if ( count( $processResult ) > 0 )
                    return true;

            } break;
            default:
                return false;


        }

        return false;
    }

    /**
     * Fetch data from list
     *
     * @todo check if objects exists
     * @return unknown_type
     */
    function getListAttributeContent()
    {
        // current
        $editionSendAttribute = eZContentObjectAttribute::fetch( $this->attribute('contentobject_attribute_id'), $this->attribute('contentobject_attribute_version'), true );

        $objectVersion = $editionSendAttribute->attribute('object_version');
        $mainParentNodeId = $objectVersion->attribute('main_parent_node_id');

        // fetch outputformats from list
        $listNode = eZContentObjectTreeNode::fetch( $mainParentNodeId );
        $listDataMap = $listNode->DataMap();
        $listAttributeContent = NULL;

        foreach ( $listDataMap as $attribute )
        {
            if ( $attribute->attribute('data_type_string') == 'cjwnewsletterlist' )
            {
               $listAttributeContent = $attribute->attribute('content');
            }
        }
        return $listAttributeContent;

    }

    /**
     * Returns array which contains objects
     *
     * @return array
     */
    function getEditionSendArray()
    {
        $objectArray = array( 'all' => CjwNewsletterEditionSend::fetchByEditionContentObjectId(
                                                    $this->attribute('contentobject_id')
                                                    ),
                              'current' => CjwNewsletterEditionSend::fetchByEditionContentObjectIdVersion(
                                                    $this->attribute('contentobject_id'),
                                                    $this->attribute('contentobject_attribute_version')
                                                    )
                            );

        return $objectArray;
    }

    /**
     * Returns current edition send object if it exists otherwise false
     *
     * @return array / boolean
     */
    function getEditionSendCurrent()
    {
        $objectArray = CjwNewsletterEditionSend::fetchByEditionContentObjectIdVersion(
                                                    $this->attribute('contentobject_id'),
                                                    $this->attribute('contentobject_attribute_version')
                                                    );

        if ( count( $objectArray ) > 0 )
        {
            return $objectArray[0];
        }
        else
        {
            return false;
        }
    }

    // newsletter_send

    /**
     * Create a new newsletter edition send object,
     * with rendered newsletteroutput
     *
     * @return object
     */
    function createNewsletterSendObject()
    {

        $sendObject = CjwNewsletterEditionSend::create( $this );
        $sendObject->store();

        return $sendObject;
    }

    /**
     * Create a xml to save all rendered outputformats as a templatedraft so as
     * later to send several newsletters
     *
     * @return xml
     */
    function createOutputXml()
    {
        $editionContentObjectId = $this->attribute('contentobject_id');
        $editionContentObjectVersion = $this->attribute('contentobject_attribute_version');

        $listAttributeContent = $this->attribute( 'list_attribute_content' );
        $outputFormatArray = $listAttributeContent->attribute( 'output_format_array' );

        $mainSiteAccess = $listAttributeContent->attribute( 'main_siteaccess' );

        $skinName = $listAttributeContent->attribute( 'skin_name' );

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $root = $dom->createElement( 'newsletter_edition_send' );
        $root = $dom->appendChild( $root );

        // in first version attribut xml_version did not exists
        $root->setAttribute( 'xml_version', '2' );

        $root->setAttribute( 'edition_contentobject_id', $editionContentObjectId );
        $root->setAttribute( 'edition_contentobject_version', $editionContentObjectVersion );
        $root->setAttribute( 'main_siteaccess', $mainSiteAccess );

        $outputFormats = $dom->createElement( 'output_formats' );
        $root->appendChild( $outputFormats );

        foreach ( $outputFormatArray  as $id => $name )
        {
            $formatArray = array( 'id' => $id,
                                  'name' => $name
                                   );

            $text = '<html>1234</html>';

            $forceNotIncludingImages = true;
            $textArray = CjwNewsletterEdition::getOutput( $editionContentObjectId, $editionContentObjectVersion, $id, $mainSiteAccess, $skinName, $forceNotIncludingImages );
            $bodyArray = $textArray['body'];

            $subject = $textArray['subject'];
            $contentType = $textArray['content_type'];
            $id = $textArray['output_format'];
            $siteURL = $textArray['site_url'];
            $ezUrl = $textArray['ez_url'];
            $ezRoot = $textArray['ez_root'];
            $locale = $textArray['locale'];
            $imageInclude = $textArray['html_mail_image_include'];

            $outputFormat = $dom->createElement( 'output_format' );

            $outputFormat->setAttribute( 'id', $id );
            $outputFormat->setAttribute( 'content_type', $contentType );
            $outputFormat->setAttribute( 'subject', $subject );
            $outputFormat->setAttribute( 'site_url', $siteURL );
            $outputFormat->setAttribute( 'ez_url', $ezUrl );
            $outputFormat->setAttribute( 'ez_root', $ezRoot );
            $outputFormat->setAttribute( 'html_mail_image_include', $imageInclude );

            $outputFormats->appendChild( $outputFormat );

            $mainTemplateNode = $dom->createElement( 'main_template' );
            foreach ( $bodyArray as $typeName => $outputString )
            {
                $typeNode = $dom->createElement( 'type' );
                $typeNode->setAttribute( 'name', $typeName );
                $typeNodeCDATA = $dom->createCDATASection( $outputString );
                $typeNode->appendChild( $typeNodeCDATA );
                $mainTemplateNode->appendChild( $typeNode );
            }
            $outputFormat->appendChild( $mainTemplateNode );
        }

        $root->setAttribute( 'locale', $textArray['locale'] );
        //$root->setAttribute( 'site_url', $textArray['site_url'] );
        //$root->setAttribute( 'ez_root', $textArray['ez_root'] );
        //$root->setAttribute( 'ez_url', $textArray['ez_url'] );
        //$root->setAttribute( 'html_mail_image_include', $imageInclude );

        $xml = $dom->saveXML();
        return $xml;
    }

    /**
     * Generate text html of a output for preview and sending email
     *
     * @see classes/cjwnewslettermail.php getAllOutputFormatTextByContentObjectVersion()
     * @see modules/newsletter/preview.php
     * @param unknown_type $editionContentObjectId
     * @param unknown_type $versionId
     * @param unknown_type $outputFormat
     * @param string $siteAccess
     * @param string $skinName
     * @param int $forceImageIncludeSettings -1 - use default settings
     *                                       1 - force do not render all img to file://settings from newsletterContentArray['html_mail_image_include'] will be used
     *                                       0 - force renders all img to file://

     * @return array
     */
    static function getOutput( $editionContentObjectId, $versionId, $outputFormat, $siteAccess, $skinName = 'default', $forceSettingImageIncludeTo = -1 )
    {
        if ( $skinName == '' )
            $skinName = 'default';

        $cjwNewsletterIni = eZINI::instance( 'cjw_newsletter.ini' );
        $phpCli = $cjwNewsletterIni->variable( 'NewsletterSettings', 'PhpCli' );

        $currentHostName = eZSys::hostname();
        $wwwDir = eZSys::wwwDir();
        //$wwwDir = 'tmp';
        $wwwDirString = '';
        if( $wwwDir != '' )
        {
            $wwwDirString = "--www_dir=$wwwDir ";
        }

        $cmd = "\"$phpCli\" extension/cjw_newsletter/bin/php/createoutput.php --object_id=$editionContentObjectId --object_version=$versionId --output_format_id=$outputFormat $wwwDirString--current_hostname=$currentHostName --skin_name=$skinName -s $siteAccess";

        $fileSep = eZSys::fileSeparator();
        $cmd = str_replace( '/', $fileSep, $cmd );

        eZDebug::writeDebug( "shell_exec( $cmd )", 'newsletter/preview' );
        // echo "<hr>$cmd<hr>";

        $returnValue = shell_exec( escapeshellcmd( $cmd ) );
        $newsletterContentArray = unserialize( trim( $returnValue ) );

        if ( CjwNewsletterEdition::imageIncludeIsEnabled() )
            $htmlMailImageInclude = 1;

        // forpreview
        $imageInclude = false;

        // render file:// if we want to force it
        // or use setting from $newsletterContentArray['html_mail_image_include']


        if ( $forceSettingImageIncludeTo === -1 && $newsletterContentArray['html_mail_image_include'] === 1 )
        {
            $imageInclude = true;
        }
        elseif ( $forceSettingImageIncludeTo === 1 )
        {
            $imageInclude = true;
        }
        elseif ( $forceSettingImageIncludeTo === 0 )
        {
            // $imageInclude = false;
        }

        if ( $imageInclude === true )
        {
            $newsletterContentArray = CjwNewsletterEdition::prepareImageInclude( $newsletterContentArray );
        }

        return $newsletterContentArray;
    }

    /**
     * prepare string => find local img files and replace http:// to file:// so it will be included by ezcomponents into the mail
     *
     * @param $newsletterContentArray
     * @return unknown_type
     */
    static function prepareImageInclude( $newsletterContentArray )
    {
        $newsletterContentArrayNew = $newsletterContentArray;

        // $outputStringArray = $outputFormatStringArray[ $outputFormatId ]['body'];
        $eZRoot = $newsletterContentArray[ 'ez_root' ] . '/';
        $eZFile = 'file://ezroot/';
        $body = $newsletterContentArray['body'];
        foreach ( $body as $id => $value )
        {
            // replace all image src from http => file:\\ezroot\ so CjwNewsletterMailComposer will embed it into the mail message
            if( $id === 'html' )
            {
                $newsletterContentArrayNew['body'][ $id ] = str_replace( "src=\"$eZRoot", "src=\"$eZFile", $value );
            }
        }
        return $newsletterContentArrayNew;
    }

    /**
     * read cjw_newsletter.ini and return true if images should inlcude in emails
     * @return unknown_type
     */
    static function imageIncludeIsEnabled()
    {
        $cjwNewsletterINI = eZINI::instance( 'cjw_newsletter.ini' );
        $imageInclude = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'ImageInclude' );
        if( $imageInclude === 'enabled' )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}

?>