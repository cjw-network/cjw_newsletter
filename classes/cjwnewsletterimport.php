<?php
/**
 * File containing the CjwNewsletterImport class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Handle Import sets of Subscriptions
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterImport extends eZPersistentObject
{

    /**
     * constructor
     *
     * @param array $row
     * @return void
     */
    function CjwNewsletterImport( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /**
     * data fields...
     *
     * @return array
     */
    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name'     => 'Id',
                                                        'datatype' => 'integer',
                                                        'default'  => 0,
                                                        'required' => true ),
                                         'type' => array( 'name'     => 'Type',
                                                          'datatype' => 'string',
                                                          'default'  => '',
                                                          'required' => true ),
                                         'list_contentobject_id' => array(
                                                        'name' => 'ListContentobjectId',
                                                        'datatype' => 'integer',
                                                        'default'  => 0,
                                                        'required' => true ),
                                         'created' => array( 'name'     => 'Created',
                                                             'datatype' => 'integer',
                                                             'default'  => 0,
                                                             'required' => true ),
                                         'creator_contentobject_id' => array(
                                                        'name'     => 'CreatorContentObjectId',
                                                        'datatype' => 'integer',
                                                        'default'  => 0,
                                                        'required' => true ),
                                         'note' => array( 'name'     => 'Note',
                                                          'datatype' => 'string',
                                                          'default'  => '',
                                                          'required' => false ),
                                         'data_xml' => array( 'name'     => 'DataXml',
                                                          'datatype' => 'string',
                                                          'default'  => '',
                                                          'required' => false ),
                                         'data_text' => array( 'name'     => 'DataText',
                                                          'datatype' => 'string',
                                                          'default'  => '',
                                                          'required' => false ),
                                         'remote_id' => array( 'name'     => 'RemoteId',
                                                          'datatype' => 'string',
                                                          'default'  => '',
                                                          'required' => true ),
                                         'imported' => array( 'name'     => 'Imported',
                                                             'datatype' => 'integer',
                                                             'default'  => 0,
                                                             'required' => true ),
                                         'imported_user_count' => array( 'name'  => 'ImportedUserCount',
                                                             'datatype' => 'integer',
                                                             'default'  => 0,
                                                             'required' => true ),
                                         'imported_subscription_count' => array( 'name'   => 'ImportedSubscriptionCount',
                                                             'datatype' => 'integer',
                                                             'default'  => 0,
                                                             'required' => true ),

                                        ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'function_attributes' => array( 'list_contentobject' => 'getListContentObject',
                                                      'creator' => 'getCreatorUserObject',
                                                      'is_imported' => 'isImported',
                                                      'imported_user_count_live' => 'getImportedUserCountLive',
                                                      'imported_subscription_count_live' => 'getImportedSubscriptionCountLive',
                                                      'imported_user_count_live_confirmed' => 'getImportedUserCountLiveConfirmed',
                                                      'imported_subscription_count_live_approved' => 'getImportedSubscriptionCountLiveApproved'
                                                      ),
                      'class_name' => 'CjwNewsletterImport',
                      'name' => 'cjwnl_import' );
    }

     /**
     * create a new import set
     *
     * @param string $type import type e.g. csv
     * @param string $note personal note for import
     * @param string $dataText here you can store what you want
     * @param string $remoteId unique number to identify importset
     * @return object / false if not create
     */
    public static function create( $listContentObjectId, $type = 'default', $note = '', $dataText = '', $remoteId = false )
    {
        if( $remoteId === false )
        {
            $remoteId = $type . ':'. CjwNewsletterUtils::generateUniqueMd5Hash( $listContentObjectId );
        }

        $row = array( 'list_contentobject_id'    => $listContentObjectId,
                      'type'                     => $type,
                      'created'                  => time(),
                      'creator_contentobject_id' => eZUser::currentUserID(),
                      'note'                     => $note,
                      'data_text'                => $dataText,
                      'remote_id'                => $remoteId );


        $newObject = new CjwNewsletterImport( $row );

        return $newObject;
    }

    /**
     * fetch CjwNewsletterImport object by id
     * return false if not found
     *
     * @param integer $id
     * @param boolean $asObject
     * @return CjwNewsletterMailboxItem or false
     */
    public static function fetch( $id, $asObject = true )
    {
         return eZPersistentObject::fetchObject(
                                                    CjwNewsletterImport::definition(),
                                                    null,
                                                    array( 'id' => (int) $id ),
                                                    $asObject
                                                );
    }

    /**
     * fetch CjwNewsletterImport object by remoteId
     * return false if not found
     *
     * @param string $remoteId
     * @param boolean $asObject
     * @return CjwNewsletterMailboxItem or false
     */
    public static function fetchByRemoteId( $remoteId, $asObject = true )
    {
         return eZPersistentObject::fetchObject(
                                                    CjwNewsletterImport::definition(),
                                                    null,
                                                    array( 'remote_id' => $remoteId ),
                                                    $asObject
                                                );
    }

    /**
     * fetch all import items
     *
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return unknown_type
     */
    static public function fetchAllImportItems( $limit = 50, $offset = 0, $sortByArray = null, $asObject = true )
    {
        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        if( !is_array( $sortByArray ))
        {
            $sortByArray = array( 'id' => true );
        }
        $condArray = array( );
        $objectList = eZPersistentObject::fetchObjectList(
                                                    self::definition(),
                                                    null,
                                                    $condArray,
                                                    $sortByArray,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );

        return $objectList;
    }

    /**
     * count all import items
     *
     * @return integer
     */
    static public function fetchAllImportItemsCount( )
    {
        $count = eZPersistentObject::count(
                             self::definition(),
                             array( ),
                             'id' );
        return $count;
    }

    /**
     * Get Creator user object
     *
     * @return unknown_type
     */
    function getCreatorUserObject()
    {
        $user = eZContentObject::fetch( $this->attribute( 'creator_contentobject_id' ) );
        return $user;
    }

    /**
     * Get List Content Object
     *
     * @return unknown_type
     */
    function getListContentObject()
    {
        if ( $this->attribute( 'list_contentobject_id' ) != 0 )
        {
            $object = eZContentObject::fetch( $this->attribute( 'list_contentobject_id' ) );
            return $object;
        }
        else
        {
            return false;
        }

    }

    /**
     * count all subcription objects which has the import_id of this object
     * @return int
     */
    function getImportedSubscriptionCountLive()
    {
        return CjwNewsletterSubscription::fetchSubscriptionListByImportIdCount( $this->attribute('id') );
    }

    /**
     * count all subcription objects which has the import_id of this object  and which has status Approved
     * @return int
     */
    function getImportedSubscriptionCountLiveApproved()
    {
        return CjwNewsletterSubscription::fetchSubscriptionListByImportIdAndStatusCount( $this->attribute('id'), CjwNewsletterSubscription::STATUS_APPROVED );
    }

    /**
     * count all nl user objects which has the import_id of this object
     * @return unknown_type
     */
    function getImportedUserCountLive()
    {
        return CjwNewsletterUser::fetchUserListByImportIdCount( $this->attribute('id') );
    }

    /**
     * count all nl user objects which has the import_id of this object and which has status Confirmed
     * @return unknown_type
     */
    function getImportedUserCountLiveConfirmed()
    {
        return CjwNewsletterUser::fetchUserListByImportIdAndStatusCount( $this->attribute('id'), CjwNewsletterUser::STATUS_CONFIRMED );
    }

    /**
     * If the importset is imported
     *
     * @return boolean
     */
    function isImported()
    {
        if ( $this->attribute( 'imported' ) > 0 )
            return true;
        else
            return false;
    }

    /**
     * when the import was done e.g. all csv data are imported
     * this function should be called to set the imported timestamp
     * and the count for imported users + subscriptions
     *
     * @return boolean
     */
    function setImported()
    {
        $this->setAttribute( 'imported_user_count', $this->getImportedUserCountLive() );
        $this->setAttribute( 'imported_subscription_count', $this->getImportedSubscriptionCountLive() );
        $this->setAttribute( 'imported', time() );
        $this->store();
    }

    /**
     * fetch all active subscriptions with current import id
     * and set status to remove by admin
     * @return array subscriptions => nl_user_id
     */
    public function removeActiveSubscriptionsByAdmin()
    {

        $count = CjwNewsletterSubscription::fetchSubscriptionListByImportIdAndStatusCount( $this->attribute('id'), CjwNewsletterSubscription::STATUS_APPROVED );

        CjwNewsletterLog::writeNotice(
                                            "CjwNewsletterImport::removeActiveSubscriptionsByAdmin",
                                            'import',
                                            'start',
                                             array( 'import_id' => $this->attribute( 'id' ),
                                                    'active_subscriptions' => $count,
                                                    'current_user'  => eZUser::currentUserID() ) );

        // count active subscriptions for import id
        $removeSubscriptionArray = array();
        $limit = 100;
        $loops = ceil($count / $limit);

        // get active subscriptions partly
        for ( $i = 0; $i < $loops; $i++ )
        {
            // get active subscriptions
            $subscriptionObjectList = CjwNewsletterSubscription::fetchSubscriptionListByImportIdAndStatus( $this->attribute('id'), CjwNewsletterSubscription::STATUS_APPROVED, $limit );
            foreach ( $subscriptionObjectList as $subscription )
            {
                $subscription->removeByAdmin();
                $removeSubscriptionArray[ $subscription->attribute('id') ] = $subscription->attribute('newsletter_user_id');
            }
        }

        $count = CjwNewsletterSubscription::fetchSubscriptionListByImportIdAndStatusCount( $this->attribute('id'), CjwNewsletterSubscription::STATUS_APPROVED );

        CjwNewsletterLog::writeNotice(
                                            "CjwNewsletterImport::removeActiveSubscriptionsByAdmin",
                                            'import',
                                            'end',
                                             array( 'import_id' => $this->attribute( 'id' ),
                                                    'subscriptions_remove_count' => count( $removeSubscriptionArray ),
                                                    'current_user'  => eZUser::currentUserID() ) );

        return $removeSubscriptionArray;
    }

}

?>