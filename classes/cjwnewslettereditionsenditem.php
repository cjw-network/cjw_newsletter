<?php
/**
 * File containing the CjwNewsletterEditionSendItem class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Mailqueue
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterEditionSendItem extends eZPersistentObject
{
    const STATUS_NEW = 0;
    const STATUS_SEND = 1;
    const STATUS_ABORT = 9;

    /**
     *
     * @param unknown_type $row
     * @return void
     */
    function CjwNewsletterEditionSendItem( $row )
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

                                         'edition_send_id' => array( 'name' => 'EditionSendId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'newsletter_user_id' => array( 'name' => 'NewsletterUserId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'subscription_id' => array( 'name' => 'NewsletterSubscriptionId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),

                                         'output_format_id' => array( 'name' => 'OutputFormatId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'processed' => array( 'name' => 'Processed',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),

                                         'status' => array( 'name' => 'Status',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'hash' => array( 'name' => 'Hash',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         'bounced' => array( 'name' => 'Bounced',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),


                                                                    ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'function_attributes' => array( 'newsletter_user_object' => 'getNewsletterUserObject',
                                                      'newsletter_subscription_object' => 'getNewsletterSubscriptionObject',
                                                      'status_string' => 'getStatusString' ),
                      'class_name' => 'CjwNewsletterEditionSendItem',
                      'name' => 'cjwnl_edition_send_item' );
    }

    /**
     *
     * @param integer $editionSendId
     * @param integer $newsletterUserId
     * @param integer $outputFormatId
     * @param integer $subscriptionId
     * @return object / boolean
     */
    static function create( $editionSendId, $newsletterUserId, $outputFormatId, $subscriptionId )
    {
        $existingObject = CjwNewsletterEditionSendItem::fetchBySendIdAndNewsletterUserIdAndOutputFormatId( $editionSendId, $newsletterUserId, $outputFormatId );

        if ( is_object( $existingObject ) )
        {
            // object exists - create no douple items
            return false;
        }
        else
        {
            $rows = array(      'edition_send_id' => (int) $editionSendId,
                                'newsletter_user_id' => (int) $newsletterUserId,
                                'output_format_id' => (int) $outputFormatId,
                                'subscription_id' => (int) $subscriptionId,
                                'hash' => CjwNewsletterUtils::generateUniqueMd5Hash( $editionSendId. '-'. $newsletterUserId ),

                                );

            $object = new CjwNewsletterEditionSendItem( $rows );

            $object->setAttribute( 'status', CjwNewsletterEditionSendItem::STATUS_NEW );
            $object->store();

            return $object;
        }
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
                switch ( $value )
                {
                    case CjwNewsletterEditionSendItem::STATUS_NEW:
                        $this->setAttribute('created', time() );
                    break;

                    case CjwNewsletterEditionSendItem::STATUS_SEND:
                        $this->setAttribute('processed', time() );
                    break;

                    case CjwNewsletterEditionSendItem::STATUS_ABORT:
                        $this->setAttribute('processed', time() );
                    break;

                    default:
                        ;
                    break;
                }

                CjwNewsletterLog::writeDebug(
                                            'set status - CjwNewsletterEditionSendItem::setAttribute',
                                            'send_item',
                                             $this->attribute('id'),
                                             array( 'status_old' => $this->attribute('status'),
                                                    'status_new' => $value,
                                                    'nl_user' => $this->attribute( 'newsletter_user_id' ) ) );

                return eZPersistentObject::setAttribute( $attr, $value );
            } break;
            default:
                return eZPersistentObject::setAttribute( $attr, $value );
        }
    }

        /**
     * get a translated string for the status code
     * @return unknown_type
     */
    function getStatusString()
    {
        $statusString = '-';
        switch( $this->attribute('status') )
        {
            case self::STATUS_NEW:
                $statusString = ezi18n( 'cjw_newsletter/editionsenditem/status', 'New' );
                break;
            case self::STATUS_SEND:
                $statusString = ezi18n( 'cjw_newsletter/editionsenditem/status', 'Send' );
                break;
            case self::STATUS_ABORT:
                $statusString = ezi18n( 'cjw_newsletter/editionsenditem/status', 'Abort' );
                break;
        }

        return $statusString;
    }


    /**
     * Fetch all send items for newsletterUserId
     *
     * @param integer $limit
     * @param integer $offset
     * @param integer $newsletterUserId
     * @param boolean $asObject
     * @return array
     */
    static function fetchListByNewsletterUserId( $limit, $offset, $newsletterUserId, $asObject = true )
    {
        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    self::definition(),
                                                    null,
                                                    array( 'newsletter_user_id' => (int) $newsletterUserId ),
                                                    null,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );

        return $objectList;

    }

    /**
     * Fetch all send items for newsletterUserId with Status
     *
     * @param integer $limit
     * @param integer $offset
     * @param integer $newsletterUserId
     * @param boolean $asObject
     * @return array
     */
    static function fetchListByNewsletterUserIdAndStatus( $limit, $offset, $newsletterUserId, $status, $asObject = true )
    {
        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    self::definition(),
                                                    null,
                                                    array( 'newsletter_user_id' => (int) $newsletterUserId,
                                                           'status' => (int) $status ),
                                                    null,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );

        return $objectList;

    }


    /**
     * Count all send items for newsletterUserId
     *
     * @param integer $newsletterUserId
     * @return integer
     */
    static function fetchListByNewsletterIdCount( $newsletterUserId )
    {
        $count = 0;
        if ( is_numeric( $newsletterUserId ) )
        {
            $db = eZDB::instance();
            $newsletterUserId = (int) $newsletterUserId;
            $sql = "SELECT count(*) AS count FROM cjwnl_edition_send_item WHERE newsletter_user_id=$newsletterUserId";
            $countArray = $db->arrayQuery( $sql );
            $count = (int) $countArray[0]['count'];
        }
        return $count;
    }

    /**
     * Search alle user who subscript to ListId
     *
     * @param integer $editionSendId
     * @param integer $newsletterUserId
     * @param integer $outputFormatId
     * @param boolean $asObject
     * @return array / boolean
     */
    static function fetchBySendIdAndNewsletterUserIdAndOutputFormatId( $editionSendId, $newsletterUserId, $outputFormatId, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                                                    CjwNewsletterEditionSendItem::definition(),
                                                    null,
                                                    array(  'edition_send_id' => (int) $editionSendId,
                                                            'newsletter_user_id' => (int) $newsletterUserId,
                                                            'output_format_id' => (int) $outputFormatId, ),
                                                    null,
                                                    null,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );

        if ( count( $objectList ) > 0 )
        {
            return $objectList[0];
        }
        else
        {
            return false;
        }
    }

    /**
     * count all bounces for a edition send id
     *
     * @param integer $editionSendId
     * @return integer
     */
    public static function fetchBounceCountByEditionSendId( $editionSendId )
    {
         $count = eZPersistentObject::count(
                             self::definition(),
                             array( 'edition_send_id' => (int) $editionSendId,
                                    'bounced' => array( '>', 0 ) ),
                             'id' );
        return $count;

    }

    /**
     *
     * @param integer $editionSendId
     * @param integer $editionSendItemStatus if false search all
     * @param boolean $asObject
     * @return integer
     */
    static function fetchListBySendIdAndStatusCount( $editionSendId, $editionSendItemStatus, $asObject = true )
    {
        $count = 0;
        if ( is_numeric( $editionSendId ) )
        {
            $db = eZDB::instance();
            $editionSendId = (int) $editionSendId;

            $sql = "SELECT count(*) AS count FROM cjwnl_edition_send_item WHERE edition_send_id=$editionSendId";

            if ( $editionSendItemStatus !== false )
            {
                $editionSendItemStatus = (int) $editionSendItemStatus;
                $sql .= " AND status=$editionSendItemStatus";
            }

            $countArray = $db->arrayQuery( $sql );
            $count = (int) $countArray[0]['count'];
        }
        return $count;
    }

    /**
     * Returns all items choose by status
     *
     * @param integer $editionSendId
     * @param integer $editionSendItemStatus
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchListSendIdAndStatus( $editionSendId, $editionSendItemStatus, $limit = 50, $offset = 0, $asObject = true )
    {
        $condArray = array( 'edition_send_id' => (int) $editionSendId );
        if ( $editionSendItemStatus !== false )
        {
           $condArray['status'] = $editionSendItemStatus;
        }

        $limitArr = null;

        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    CjwNewsletterEditionSendItem::definition(),
                                                    null,
                                                    $condArray,
                                                    null,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );

        return $objectList;
    }

    /**
     *
     * @param unknown_type $editionSendItemStatus
     * @param boolean $asObject
     * @return integer
     */
    static function fetchListByStatusCount( $editionSendItemStatus, $asObject = true )
    {
        $count = 0;
        if ( is_numeric( $editionSendItemStatus ) )
        {
            $db = eZDB::instance();
            $editionSendItemStatus = (int) $editionSendItemStatus;

            $sql = "SELECT count(*) AS count FROM cjwnl_edition_send_item WHERE status=$editionSendItemStatus";
            $countArray = $db->arrayQuery( $sql );
            $count = (int) $countArray[0]['count'];
        }
        return $count;
    }

    /**
     *
     * @return unknown_type
     */
    function getNewsletterUserObject()
    {
        return CjwNewsletterUser::fetch( $this->attribute('newsletter_user_id') );
    }

    /**
     *
     * @return unknown_type
     */
    function getNewsletterSubscriptionObject()
    {
        return CjwNewsletterSubscription::fetch( $this->attribute('subscription_id') );
    }

    /**
     * fetcht edition_send_item object from hash code
     *
     * @param string $hashCode
     * @return object / boolean
     */
    public static function fetchByHash( $hashCode, $asObject )
    {
         return eZPersistentObject::fetchObject(
                                                    CjwNewsletterEditionSendItem::definition(),
                                                    null,
                                                    array( 'hash' => $hashCode ),
                                                    $asObject
                                                );
    }

    /**
     * set bounced to current timestamp so we know that this item has a bouncemail detected
     * by this system
     *
     * return integer (timestamp)
     */
    public function setBounced()
    {
        $timestamp = time();
        $this->setAttribute( 'bounced', $timestamp );
        $this->store();
        return $timestamp;
    }

    /**
     * this is called after parsing bounce mails or blacklist
     * if a user is blacklisted or bounced all active items ( STATUS_NEW ) should be set to abort
     *
     * @param int $newsletterUserId
     * @return int count of sendItems which are aborted
     */
    static function setAllActiveItemsToStatusAbortByNewsletterUserId( $newsletterUserId )
    {
        // fetch all items which are not send out by user
        $sendItemList = self::fetchListByNewsletterUserIdAndStatus( 200, 0, $newsletterUserId, self::STATUS_NEW, true );
        foreach ( $sendItemList as $sendItem )
        {
            $sendItem->setAttribute( 'status' , self::STATUS_ABORT );
            $sendItem->store();
        }
        return count( $sendItemList );
    }
}

?>