<?php
/**
 * File containing the CjwNewsletterEditionSend class
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Class description here
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterEditionSend extends eZPersistentObject
{
    const STATUS_WAIT_FOR_SCHEDULE = 4;
    const STATUS_WAIT_FOR_PROCESS = 0;
    const STATUS_MAILQUEUE_CREATED = 1;
    const STATUS_MAILQUEUE_PROCESS_STARTED = 2;
    const STATUS_MAILQUEUE_PROCESS_FINISHED = 3;
    const STATUS_ABORT = 9;

    /**
     *
     * @param unknown_type $row
     * @return void
     */
    function CjwNewsletterEditionSend( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /**
     * @return void
     */
    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'Id',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),

                                         'edition_contentobject_id' => array( 'name' => 'EditionContentObjectId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'edition_contentobject_version' => array( 'name' => 'EditionContentObjectVersion',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'list_contentobject_id' => array( 'name' => 'ListContentObjectId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'list_contentobject_version' => array( 'name' => 'ListContentObjectVersion',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'list_is_virtual' => array( 'name' => 'ListIsVirtual',
                                                                        'datatype' => 'Integer',
                                                                        'default' => 0,
                                                                        'required' => true ),
                                         'siteaccess' => array( 'name' => 'SiteAccess',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => true ),
                                         'output_format_array_string' => array( 'name' => 'OutputFormatArrayString',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => true ),
                                         'creator_id' => array( 'name' => 'CreatorId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'mailqueue_created' => array( 'name' => 'MailQueueCreated',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'mailqueue_process_scheduled' => array( 'name' => 'MailQueueProcessScheduled',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'mailqueue_process_started' => array( 'name' => 'MailQueueProcessStarted',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'mailqueue_process_finished' => array( 'name' => 'MailQueueProcessFinished',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'mailqueue_process_aborted' => array( 'name' => 'MailQueueProcessAborted',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'status' => array( 'name' => 'Status',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'output_xml' => array( 'name' => 'OutputXml',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => true ),
                                         'hash' => array( 'name' => 'Hash',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         'email_sender' => array( 'name' => 'EmailSender',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => true ),
                                         'email_sender_name' => array( 'name' => 'EmailSenderName',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => true ),
                                         'email_reply_to' => array( 'name' => 'EmailReplyTo',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         'email_return_path' => array( 'name' => 'EmailReturnPath',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         'personalize_content' => array( 'name' => 'PersonalizeContent',
                                                                                        'datatype' => 'Integer',
                                                                                        'default' => 0,
                                                                                        'required' => false ),
                                                                                           ),


                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'function_attributes' => array( 'send_items_statistic' => 'getSendItemsStatistic',
                                                      'output_format_array' => 'getOutputFormatArray' ),
                      'class_name' => 'CjwNewsletterEditionSend',
                      'name' => 'cjwnl_edition_send' );
    }

    /**
     * Create new CjwNewsletterEditionSend object
     *
     * @param CjwNewsletterEdition $editionObject
     * @return object
     */
    static function create( CjwNewsletterEdition $editionObject, $schedule = null )
    {

        $editionContentObjectId = $editionObject->attribute('contentobject_id');
        $editionContentObjectVersion = $editionObject->attribute('contentobject_attribute_version');

        $user = eZUser::currentUser();
        $creatorId = $user->attribute( 'contentobject_id' );

        // $outputXml = '<xml>hali hallo</xml>';

        $outputXml = $editionObject->createOutputXml();

        $listAttributeContent = $editionObject->attribute( 'list_attribute_content' );
        $listContentObjectId = $listAttributeContent->attribute( 'contentobject_id' );
        $listContentObjectVersion = $listAttributeContent->attribute( 'contentobject_attribute_version' );
        $listIsVirtual = $listAttributeContent->attribute( 'is_virtual' );

        $outputFormatArrayString = $listAttributeContent->attribute( 'output_format_array_string' );

        $mainSiteAccess = $listAttributeContent->attribute( 'main_siteaccess' );

        $emailSender = $listAttributeContent->attribute( 'email_sender' );
        $emailSenderName = $listAttributeContent->attribute( 'email_sender_name' );
        $emailReplyTo = $listAttributeContent->attribute( 'email_reply_to' );
        $emailReturnPath = $listAttributeContent->attribute( 'email_return_path' );


        $personalizeContent = $listAttributeContent->attribute( 'personalize_content' );

        $rows = array(
                            'list_contentobject_id' => $listContentObjectId,
                            'list_contentobject_version' => $listContentObjectVersion,
                            'list_is_virtual' => $listIsVirtual,
                            'edition_contentobject_id' => $editionContentObjectId,
                            'edition_contentobject_version' => $editionContentObjectVersion,
                            'siteaccess' => $mainSiteAccess,
                            'output_format_array_string' => $outputFormatArrayString,
                            'created' => time(),
                            'creator_id' => $creatorId,
                            'status' => CjwNewsletterEditionSend::STATUS_WAIT_FOR_SCHEDULE,
                            'mailqueue_process_scheduled' => is_null($schedule) ? time() : $schedule,
                            'output_xml' => $outputXml,
                            'hash' => CjwNewsletterUtils::generateUniqueMd5Hash( $listContentObjectId. $editionContentObjectId. $editionContentObjectVersion ),
                            'email_sender' => $emailSender,
                            'email_sender_name' => $emailSenderName,
                            'email_reply_to' => $emailReplyTo,
                            'email_return_path' => $emailReturnPath,
                            'personalize_content' => $personalizeContent
                            );

        $object = new CjwNewsletterEditionSend( $rows );
        return $object;
    }

    /**
     * (non-PHPdoc)
     * @see kernel/classes/eZPersistentObject#setAttribute($attr, $val)
     */
    function setAttribute( $attr, $value )
    {

        switch ( $attr )
        {
            case 'status':
            {
                if ( $value === CjwNewsletterEditionSend::STATUS_MAILQUEUE_CREATED )
                {
                    $this->setAttribute('mailqueue_created', time() );

                }
                elseif ( $value === CjwNewsletterEditionSend::STATUS_MAILQUEUE_PROCESS_STARTED )
                {
                    $this->setAttribute('mailqueue_process_started', time() );
                }
                elseif ( $value === CjwNewsletterEditionSend::STATUS_MAILQUEUE_PROCESS_FINISHED )
                {
                    $this->setAttribute('mailqueue_process_finished', time() );
                }
                elseif ( $value === CjwNewsletterEditionSend::STATUS_ABORT )
                {
                    $this->setAttribute('mailqueue_process_aborted', time() );
                }
                return eZPersistentObject::setAttribute( $attr, $value );
            } break;
            default:
                return eZPersistentObject::setAttribute( $attr, $value );
        }

    }

    /**
     * Set modified Date of the edtion object, so you can sort by in the admin interface
     *
     * @return void
     */
    function setEditionObjectModified()
    {
        $object = eZContentObject::fetch( $this->attribute('edition_contentobject_id') );
        if ( is_object( $object ) )
        {
            $object->setAttribute( 'modified', time() );
            $object->store();
        }
    }

    /**
     * reimplementation
     * update edtionObject modified date, so you can sort_by in admin interface
     *
     * @param unknown_type $fieldFilters
     * @return void
     */
    function store( $fieldFilters = null )
    {
        $this->setEditionObjectModified();
        parent::storeObject( $this, $fieldFilters );
    }


    // fetch funktionen
    // ######################################

    /**
     * Fetch all send_objects for a editionid and editionversionid
     *
     * @param unknown_type $editionContentobjectId
     * @param unknown_type $editionContentOjbectVersion
     * @param boolean $asObject
     * @return array
     */
    static function fetchByEditionContentObjectIdVersion( $editionContentobjectId, $editionContentOjbectVersion, $asObject = true  )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterEditionSend::definition(),
                        null,
                        array( 'edition_contentobject_id' => $editionContentobjectId,
                               'edition_contentobject_version' => $editionContentOjbectVersion  ),
                        null,
                        null,
                        $asObject
                        );

        return $objectList;
    }

    /**
     * Fetch a send object by hash
     *
     * @param array $editionSendHash
     * @param boolean $asObject
     * @return array
     */
    static function fetchByHash( $editionSendHash, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterEditionSend::definition(),
                        null,
                        array( 'hash' => $editionSendHash ),
                        null,
                        null,
                        $asObject
                        );

        if ( count( $objectList ) > 0 )
            return $objectList[0];
    }

    /**
     * Fetch all send_objects for a editionid
     *
     * @param unknown_type $editionContentobjectId
     * @param boolean $asObject
     * @return array
     */
    static function fetchByEditionContentObjectId( $editionContentobjectId, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterEditionSend::definition(),
                        null,
                        array( 'edition_contentobject_id' => $editionContentobjectId ),
                        null,
                        null,
                        $asObject
                        );

        return $objectList;
    }

    /**
     * Fetch all send_objects for an  editionContentOjbectId with send_status
     * ignore version
     *
     * @param unknown_type $editionContentobjectId
     * @param array $sendStatusArray
     * @param boolean $asObject
     * @return array
     */
    static function fetchByEditionContentObjectIdAndStatus( $editionContentobjectId, $sendStatusArray, $asObject = true  )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterEditionSend::definition(),
                        null,
                        array( 'edition_contentobject_id' => $editionContentobjectId,
                               'status' => array( $sendStatusArray ) ), // array( array( s1, s3 ) ) =>  status in ( s1, s2 ) )
                        null,
                        null,
                        $asObject
                        );

        return $objectList;
    }

    /**
     * Fetch all send_objects for an  editionContentOjbectId + version with send_status
     *
     * @param integer $editionContentobjectId
     * @param unknown_type $editionContentOjbectVersion
     * @param array $sendStatusArray
     * @param boolean $asObject
     * @return array
     */
    static function fetchByEditionContentObjectIdVersionAndStatus( $editionContentobjectId, $editionContentOjbectVersion, $sendStatusArray, $asObject = true  )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterEditionSend::definition(),
                        null,
                        array( 'edition_contentobject_id' => $editionContentobjectId,
                               'edition_contentobject_version' => $editionContentOjbectVersion,
                               'status' => array( $sendStatusArray ) ), // array( array( s1, s3 ) ) =>  status in ( s1, s2 ) )
                        null,
                        null,
                        $asObject
                        );

        return $objectList;
    }

    /**
     * Fetch all newsletter send objects whith status
     *
     * @param array $status
     * @param bool $asObject
     * @return array
     */
    public static function fetchEditionSendListByStatus( array $sendStatusArray, $asObject = true )
    {
         $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterEditionSend::definition(),
                        null,
                        array( 'status' =>array( $sendStatusArray ) ), // array( array( s1, s3 ) ) =>  status in ( s1, s2 ) )
                        null,
                        null,
                        $asObject
                        );

        return $objectList;
    }

    /**
     * Statistic data about sending process
     *
     * @return array
     */
    function getSendItemsStatistic()
    {
        $editionSendId = $this->attribute('id');
        $itemsCount = CjwNewsletterEditionSendItem::fetchListBySendIdAndStatusCount( $editionSendId, false );
        $itemsNotSend = CjwNewsletterEditionSendItem::fetchListBySendIdAndStatusCount( $editionSendId, CjwNewsletterEditionSendItem::STATUS_NEW );
        $itemsSend = CjwNewsletterEditionSendItem::fetchListBySendIdAndStatusCount( $editionSendId, CjwNewsletterEditionSendItem::STATUS_SEND  );
        $itemsAborted = $itemsCount - $itemsNotSend - $itemsSend;
        $itemsBounced = CjwNewsletterEditionSendItem::fetchBounceCountByEditionSendId ( $editionSendId );

        $itemsSendInPersent = 0;
        // catch division by zero
        if( $itemsCount > 0 )
        {
            $itemsSendInPersent = round( $itemsSend / $itemsCount * 100, 1 );
        }

        return array( 'items_count' => $itemsCount,
                      'items_not_send' => $itemsNotSend,
                      'items_send' => $itemsSend,
                      'items_abort' => $itemsAborted,
                      'items_send_in_percent' => $itemsSendInPersent,
                      'items_bounced' => $itemsBounced
                    );
    }

    /**
     * Returns available outputformats
     * array( id => name )
     * zb. array['0'] = 'html'
     *
     * @return array
     */
    function getOutputFormatArray()
    {
        $availableOutputFormatArray = CjwNewsletterList::getAvailableOutputFormatArray();

        $outputFormatArray = CjwNewsletterList::stringToArray( eZPersistentObject::attribute( 'output_format_array_string' ) );

        $newOutputFormatArrayWithNames = array();
        foreach ( $outputFormatArray as $outputFormatId )
        {
            if ( array_key_exists( $outputFormatId, $availableOutputFormatArray ) )
                $newOutputFormatArrayWithNames[ $outputFormatId ] = $availableOutputFormatArray[ $outputFormatId ];
        }

        return $newOutputFormatArrayWithNames;
    }

    /**
     * Return an array  arr['text']=emailtext
     *                  arr['html']=htmlpart
     *
     * extrakt from outputXml
     *
     * @return array
     */
    function getParsedOutputXml()
    {

        $resultArray = array();

        $subject = 'subject';
        $html = 'html';
        $text = 'text';

        $xmlString = $this->attribute( 'output_xml' );

        $doc = new DOMDocument();
        $doc->loadXML( $xmlString );

        $outputFormatNodes = $doc->getElementsByTagName( 'output_formats' )->item(0);

        // first to create a list of categories
        $outputFormatArray = array();
        $xmlOutputFormats = $doc->getElementsByTagName( 'output_formats' )->item(0);

        foreach ( $xmlOutputFormats->getElementsByTagName( 'output_format' ) as $outputFormatNode )
        {
            // notice how we get attributes
            $outputFormatId = $outputFormatNode->getAttribute( 'id' );
            $contentType = $outputFormatNode->getAttribute( 'content_type' );
            $subject = $outputFormatNode->getAttribute( 'subject' );

            // TODO check if ez_root and ez_url exists;
            $ezRoot = $outputFormatNode->getAttribute( 'ez_root' );
            $ezUrl = $outputFormatNode->getAttribute( 'ez_url' );

            // TODO check if html_mail_image_include include in xml
            $htmlMailImageInclude = 0;
            $htmlMailImageInclude = (int) $outputFormatNode->getAttribute( 'html_mail_image_include' );

            $html = '';
            $text = '';

            // <main_template>
            $mainTemplateNode = $outputFormatNode->getElementsByTagName( 'main_template' )->item(0);

            // <type name="html"> <type name="text">
            foreach ( $mainTemplateNode->getElementsByTagName( 'type' ) as $typeNode )
            {
                $typeName = $typeNode->getAttribute( 'name' );
                switch ( $typeName )
                {
                    case 'html':
                        $html = $typeNode->nodeValue;
                        break;
                    case 'text':
                        $text = $typeNode->nodeValue;
                        break;
                }
            }

            $resultArray[ $outputFormatId ]['subject'] = $subject;
            $resultArray[ $outputFormatId ]['ez_root'] = $ezRoot;
            $resultArray[ $outputFormatId ]['ez_url'] = $ezUrl;
            $resultArray[ $outputFormatId ]['html_mail_image_include'] = $htmlMailImageInclude;


            $resultArray[ $outputFormatId ]['body'] = array( 'html' => $html,
                                                             'text' => $text );
        }
        return $resultArray;
    }

    /**
     * Return userobject by id
     *
     * @param integer $id
     * @return object
     */
    static function fetch( $id )
    {
        $object = eZPersistentObject::fetchObject(
                            CjwNewsletterEditionSend::definition(),
                            null,
                            array( 'id' => $id ),
                            true
                            );

        return $object;
    }

    /**
     * Set status for editionsend and all editionsenditems on ABORT(9), so these will
     * ignore by the cronjob
     *
     * @return SQL Command
     */
    function abortAllSendItems()
    {
        // all sendObject Items auf 9 setzen
        $editionSendId = $this->attribute('id');

        $db = eZDB::instance();
        $editionSendItemStatusAbort = CjwNewsletterEditionSendItem::STATUS_ABORT;
        $query = "UPDATE `cjwnl_edition_send_item` SET `status` = '$editionSendItemStatusAbort'
                  WHERE `cjwnl_edition_send_item`.`edition_send_id` =$editionSendId
                  AND `cjwnl_edition_send_item`.`status` =0;";

        $updateResult = $db->query( $query );

        // sendObject->set status on 9
        $this->setAttribute( 'status', CjwNewsletterEditionSend::STATUS_ABORT );
        $this->store();

        return $updateResult;
    }


    /**
    * @see in cronjob create
    *
    * should handle the correct fetch of subscripers static + virtual
    *
    */
    function getSubscriptionObjectArray( $subscriptionStatus = CjwNewsletterSubscription::STATUS_APPROVED,
                                         $limit = 0,
                                         $offset = 0 )
    {
        $listContentObjectId = $this->attribute( 'list_contentobject_id' );
        $listContentObjectVersion = $this->attribute( 'list_contentobject_version' );
        $listIsVirtual = $this->attribute( 'list_is_virtual' );

        $subscriptionObjectList = false;

        // static list (CjwNewsletterList) or virtual (CjwNewsletterListVirtual)
        $listObject = CjwNewsletterList::fetchByListObjectVersion( $listContentObjectId, $listContentObjectVersion );

        if ( is_object( $listObject ) )
        {
            $subscriptionObjectList = $listObject->getSubscriptionObjectArray( $subscriptionStatus, $limit, $offset );
        }

        return $subscriptionObjectList;

    }

}

?>
