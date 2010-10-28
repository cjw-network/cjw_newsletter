<?php
/**
 * File containing the CjwNewsletterSubscription class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Class description here
 *
 * @version 1.0.0beta
 * @package cjw_newsletter
 */

require_once( 'kernel/common/i18n.php' );

class CjwNewsletterSubscription extends eZPersistentObject
{

    const STATUS_PENDING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_APPROVED = 2;

    const STATUS_REMOVED_SELF = 3;
    const STATUS_REMOVED_ADMIN = 4;

    /**
     * @var int if nl user was deactive by a soft bounce
     */
    const STATUS_BOUNCED_SOFT = 6;
    /**
     * @var int if nl user was deactive by a hard bounce
     */
    const STATUS_BOUNCED_HARD = 7;
    /**
     * @var int if newsletter user has this status he get no emails anymore
     */
    const STATUS_BLACKLISTED = 8;


    const OUTPUT_FORMAT_HTML = 0;
    const OUTPUT_FORMAT_TEXT = 1;

    /**
     * Constructor
     *
     * @param unknown_type $row
     * @return void
     */
    function CjwNewsletterSubscription( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /**
     * @return void
     */
    static function definition()
    {
        return array( 'fields' => array(
                                         'id' => array( 'name' => 'Id',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),


                                         'list_contentobject_id' => array( 'name' => 'ListContentObjectId',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true ),
                                         'newsletter_user_id' => array( 'name' => 'NewsletterUserId',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true ),
                                         'hash' => array( 'name' => 'Hash',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'output_format_array_string' => array( 'name' => 'OutputFormat',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         'creator_contentobject_id' => array( 'name' => 'CreatorContentObjectId',
                                                                'datatype' => 'interger',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'interger',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modifier_contentobject_id' => array( 'name' => 'ModifierContentObjectId',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'confirmed' => array( 'name' => 'Confirmed',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'approved' => array( 'name' => 'Approved',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'removed' => array( 'name' => 'Removed',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'remote_id' => array( 'name' => 'RemoteId',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         'import_id' => array( 'name' => 'ImportId',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => false ),



                ),
                      'function_attributes' => array( 'output_format_array' => 'getOutputFormatArray',
                                                      'newsletter_user' => 'getNewsletterUserObject',// admin content/view/newsletter_list_subscription_list
                                                      'newsletter_list' => 'getNewsletterListObject',
                                                      'newsletter_list_attribute_content' => 'getNewsletterListAttributeContent',
                                                      'is_removed' => 'isRemoved',
                                                      'is_removed_self' => 'isRemovedSelf',
                                                      'is_blacklisted' => 'isBlacklisted',
                                                      'creator' => 'getCreatorUserObject',
                                                      'modifier' => 'getModifierUserObject',
                                                      'status_string' => 'getStatusString'
                                                      // 'usersubscriptiondata' => 'userSubscriptionData'
                                                      ),
                      // 'keys' => array( 'list_contentobject_id', 'newsletter_user_id' ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'sort' => array( 'id' => 'asc' ),
                      'class_name' => 'CjwNewsletterSubscription',
                      'name' => 'cjwnl_subscription' );
    }

    /**
     * Create new CjwNewsletterSubscription object
     *
     * @param integer $listContentObjectId
     * @param integer $newsletterUserId
     * @param array $outputFormatArray
     * @param unknown_type $status
     * @return object
     */
    static function create( $listContentObjectId,
                            $newsletterUserId,
                            $outputFormatArray,
                            $status =  CjwNewsletterSubscription::STATUS_PENDING,
                            $context = 'default' )
    {
        $rows = array( 'created' => time(),
                       'list_contentobject_id' => $listContentObjectId,
                       'newsletter_user_id' => $newsletterUserId,
                       'output_format_array_string' => CjwNewsletterSubscription::arrayToString( $outputFormatArray ),
                       'creator_contentobject_id' => eZUser::currentUserID(),
                       'hash' => CjwNewsletterUtils::generateUniqueMd5Hash( $newsletterUserId ),
                       'remote_id' => 'cjwnl:'. $context .':' .CjwNewsletterUtils::generateUniqueMd5Hash( $newsletterUserId ),
                       'status' => $status );

        $object = new CjwNewsletterSubscription( $rows );
        // status nochmals setzen, damit evtl. automatisches status änderung funzt
        $object->setAttribute( 'status', $status );
        return $object;
    }

    /**
     * Helpfunction
     *
     * convert array to string
     * ;$1;$2;$3;
     * for searching : begin and end is ";"
     * like %;$1;%
     *
     * @param array $array
     * @return string
     */
    static function arrayToString( $array )
    {
        return  ';' . implode( ';', $array ) . ';';
    }

    /**
     * Helpfunction
     * convert string to array
     *
     * @param string $string
     * @return array
     */
    static function stringToArray( $string )
    {
        return  explode( ';', $string );
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
                $currentTimeStamp = time();
                // status timestamps setzen
                switch ( $value )
                {
                    case CjwNewsletterSubscription::STATUS_CONFIRMED :
                    {
                        $this->setAttribute( 'removed', 0 );
                        $this->setAttribute( 'confirmed', $currentTimeStamp );
                        $newsletterListAttributeContent = $this->attribute( 'newsletter_list_attribute_content' );

                        // set approve automatically if defined in list config
                        if ( is_object( $newsletterListAttributeContent ) and (int) $newsletterListAttributeContent->attribute('auto_approve_registered_user') == 1 )
                        {
                            $this->setAttribute( 'approved', $currentTimeStamp );
                            $value = CjwNewsletterSubscription::STATUS_APPROVED;
                        }

                    } break;

                    case CjwNewsletterSubscription::STATUS_APPROVED:
                    {
                        $this->setAttribute( 'approved', $currentTimeStamp );
                        $this->setAttribute( 'removed', 0 );
                    } break;

                    case CjwNewsletterSubscription::STATUS_REMOVED_ADMIN:
                    case CjwNewsletterSubscription::STATUS_REMOVED_SELF:
                    case CjwNewsletterSubscription::STATUS_BLACKLISTED:
                    case CjwNewsletterSubscription::STATUS_BOUNCED_SOFT:
                    case CjwNewsletterSubscription::STATUS_BOUNCED_HARD:
                    {
                        $this->setAttribute( 'approved', 0 );
                        $this->setAttribute( 'removed', $currentTimeStamp );
                    } break;
                }
                $this->setAttribute( 'modified', $currentTimeStamp );

                $statusOld = $this->attribute( 'status' );
                $statusNew = $value;

                if( $statusOld != $statusNew )
                {

                    CjwNewsletterLog::writeNotice(
                                                'CjwNewsletterSubscription::setAttribute',
                                                'subscription',
                                                'status',
                                                 array( 'status_old'        => $statusOld,
                                                        'status_new'        => $statusNew,
                                                        'subscription_id'   => $this->attribute( 'id' ),
                                                        'list_id'           => $this->attribute( 'list_contentobject_id' ),
                                                        'nl_user'           => $this->attribute( 'newsletter_user_id' ),
                                                        'modifier'          => eZUser::currentUserID() ) );
                }
                else
                {
                     CjwNewsletterLog::writeDebug(
                                                'CjwNewsletterSubscription::setAttribute',
                                                'subscription',
                                                'status',
                                                 array( 'status_old'        => $statusOld,
                                                        'status_new'        => $statusNew,
                                                        'subscription_id'   => $this->attribute( 'id' ),
                                                        'list_id'           => $this->attribute( 'list_contentobject_id' ),
                                                        'nl_user'           => $this->attribute( 'newsletter_user_id' ),
                                                        'modifier'          => eZUser::currentUserID() ) );
                }

                eZPersistentObject::setAttribute( $attr, $value );

            } break;
            default:
            {
                eZPersistentObject::setAttribute( $attr, $value );
            } break;
        }
    }

    /**
     * Check if current object has a removestatus
     *
     * @return boolean
     */
    function isRemoved()
    {
        $subscriptionStatus = $this->attribute('status');
        if ( $subscriptionStatus == CjwNewsletterSubscription::STATUS_REMOVED_ADMIN
            || $subscriptionStatus == CjwNewsletterSubscription::STATUS_REMOVED_SELF )
            return true;
        else
            return false;
    }

    /**
     * Check if current object has a status remove self
     *
     * @return boolean
     */
    function isRemovedSelf()
    {
        $subscriptionStatus = $this->attribute('status');
        if ( $subscriptionStatus == CjwNewsletterSubscription::STATUS_REMOVED_SELF )
            return true;
        else
            return false;
    }

    /**
     * Check if current object has a status blacklisted
     *
     * @return boolean
     */
    function isBlacklisted()
    {
        $subscriptionStatus = $this->attribute('status');
        if ( $subscriptionStatus == CjwNewsletterSubscription::STATUS_BLACKLISTED )
            return true;
        else
            return false;
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
            case self::STATUS_PENDING:
                $statusString = ezi18n( 'cjw_newsletter/subscription/status', 'Pending' );
                break;
            case self::STATUS_CONFIRMED:
                $statusString = ezi18n( 'cjw_newsletter/subscription/status', 'Confirmed' );
                break;
            case self::STATUS_APPROVED:
                $statusString = ezi18n( 'cjw_newsletter/subscription/status', 'Approved' );
                break;
            case self::STATUS_REMOVED_SELF:
                $statusString = ezi18n( 'cjw_newsletter/subscription/status', 'Removed by user' );
                break;
            case self::STATUS_REMOVED_ADMIN:
                $statusString = ezi18n( 'cjw_newsletter/subscription/status', 'Removed by admin' );
                break;
            case self::STATUS_BOUNCED_SOFT:
                $statusString = ezi18n( 'cjw_newsletter/subscription/status', 'Bounced soft' );
                break;
            case self::STATUS_BOUNCED_HARD:
                $statusString = ezi18n( 'cjw_newsletter/subscription/status', 'Bounced hard' );
                break;
            case self::STATUS_BLACKLISTED:
                $statusString = ezi18n( 'cjw_newsletter/subscription/status', 'Blacklisted' );
                break;
        }

        return $statusString;
    }

    /**
     * Return user newsletterUserObject
     *
     * @return object
     */
    function getNewsletterUserObject()
    {
        $userObject = CjwNewsletterUser::fetch( $this->attribute('newsletter_user_id'));
        return $userObject;
    }

    /**
     * Return user newsletterListObject
     *
     * @return object
     */
    function getNewsletterListObject()
    {
        $object = eZContentObject::fetch( $this->attribute('list_contentobject_id'));
        return $object;
    }

    /**
     * Return user newsletterListObject
     *
     * @return object / boolean
     */
    function getNewsletterListAttributeContent()
    {
        $object = eZContentObject::fetch( $this->attribute('list_contentobject_id'));
        if ( is_object($object) )
        {
            $dataMap = $object->attribute('data_map');

            if ( array_key_exists( 'newsletter_list', $dataMap ) )
            {
                $newsletterListAttribute = $dataMap['newsletter_list'];
                $newsletterListAttributeContent = $newsletterListAttribute->attribute('content');
                return $newsletterListAttributeContent;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns available outputformats as array
     * array( id => name )
     * zb. array['0'] = 'html'
     *
     * @return array
     */
    function getOutputFormatArray()
    {
        $availableOutputFormatArray = CjwNewsletterList::getAvailableOutputFormatArray();

        $outputFormatArray = $this->stringToArray( eZPersistentObject::attribute( 'output_format_array_string' ) );

        $newOutputFormatArrayWithNames = array();
        foreach ( $outputFormatArray as $outputFormatId )
        {
            if ( array_key_exists( $outputFormatId, $availableOutputFormatArray ) )
                $newOutputFormatArrayWithNames[ $outputFormatId ] = $availableOutputFormatArray[ $outputFormatId ];
        }

        return $newOutputFormatArrayWithNames;
    }

    /**
     * Get user object
     *
     * @return unknown_type
     */
    function getModifierUserObject()
    {
        $retVal = false;
        if ( $this->attribute( 'modifier_contentobject_id' ) != 0 )
        {
            $retVal = eZContentObject::fetch( $this->attribute( 'modifier_contentobject_id' ) );
        }
        return $retVal;
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
     * check current subscription if it has status Pending
     * if yes than confirm this subscription
     * used by confirmAll in CjwNewsletterUser->confirmAll
     *
     * @return boolean
     */
    function confirm()
    {
        if ( $this->attribute('status') == CjwNewsletterSubscription::STATUS_PENDING )
        {
            // timestamp + status setzen
            $this->setAttribute('status', CjwNewsletterSubscription::STATUS_CONFIRMED );
            $this->store();
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Unsubscribe subscription only if not blacklisted or if not removed self
     *
     * @return boolean
     */
    public function unsubscribe()
    {
        //$this->setAttribute( 'output_format_array_string', CjwNewsletterSubscription::arrayToString(  array() ) );
        if ( $this->attribute('status') == CjwNewsletterSubscription::STATUS_BLACKLISTED
          || $this->attribute('status') == CjwNewsletterSubscription::STATUS_REMOVED_SELF )
        {
            return false;
        }
        else
        {
            $this->setAttribute( 'status', CjwNewsletterSubscription::STATUS_REMOVED_SELF );
            $this->sync();
            $this->store();
            return true;
        }
    }

    /**
     * remove subscription by admin
     *
     * @return void
     */
    public function removeByAdmin()
    {
        $this->setAttribute( 'status', CjwNewsletterSubscription::STATUS_REMOVED_ADMIN );
        $this->sync();
        $this->store();
    }

    /**
     * approve subscription by admin
     *
     * @return void
     */
    public function approveByAdmin()
    {
        $this->setAttribute( 'status', CjwNewsletterSubscription::STATUS_APPROVED );
        $this->sync();
        $this->store();
    }

    /**
     * Synchronous registration / deregistration for several lists with an array
     * if id_array has more elements than list_array, than is a deregistration defined
     * the difference of id_array and list_array means that these elements shouldn't
     * has subscriptions
     *
     * $subscriptionDataArr = array();
     * $subscriptionDataArr['ez_user_id']
     * $subscriptionDataArr['salutation']
     * $subscriptionDataArr['first_name'] = $http->postVariable( 'Subscription_FirstName' );
     * $subscriptionDataArr['name'] = $http->postVariable( 'Subscription_Name' );
     * $subscriptionDataArr['email'] = $http->postVariable( 'Subscription_Email' );
     *
     * $subscriptionDataArr['id_array'] = $http->postVariable( 'Subscription_IdArray' );
     * $subscriptionDataArr['list_array'] = $http->postVariable( 'Subscription_ListArray' );
     * $subscriptionDataArr['list_output_format_array'] = $http->postVariable( 'Subscription_OutputFormatArray' );
     *
     * @param array $subscriptionDataArr
     * @param $newNewsletterUserStatus status for new created nl users e.g. CjwNewsletterUser::STATUS_PENDING
     * @param $subscribeOnlyMode if true than no subscription will be removed used if subscription is done as ez_user
     * @param $context subscribe | configure | user_edit | datatype_edit | datatype_collect | csvimport from which context the function is called
     * @return array
     */
    static function createSubscriptionByArray( $subscriptionDataArr,
                                               $newNewsletterUserStatus = CjwNewsletterUser::STATUS_PENDING,
                                               $subscribeOnlyMode = false,
                                               $context = 'default' )
    {
        $resultArray = array();
        $resultArray['list_subscribe'] = array();
        $resultArray['list_remove'] = array();
        $resultArray['errors'] = array();

        $email = $subscriptionDataArr[ 'email' ];
        if( isset( $subscriptionDataArr[ 'salutation' ] ) )
        {
            $salutation = $subscriptionDataArr[ 'salutation' ];
        }
        else
        {
            $salutation = null;
        }

        $firstName = $subscriptionDataArr['first_name'];
        $lastName = $subscriptionDataArr['last_name'];
        $eZUserId = (int) $subscriptionDataArr['ez_user_id'];

         // new value form POST
        $newEzUserId = $eZUserId;
        $newEmail = $email;

        // TODO return here the nl user object for update + status
        $checkResult = CjwNewsletterUser::checkIfUserCanBeUpdated( $email, $eZUserId, $updateNewEmail = true );
        switch ( $checkResult )
        {
            // create new user
            case 40:
                break;
            // update user
            case 41:
                break;
            // update user with new mail
            case 42:

                break;
            case -20:
            case -1:
                if( $context == 'subscribe' )
                {
                    eZDebug::writeDebug( "checkResult[$checkResult] - CjwNewsletterSubscription::createSubscriptionByArray return false because email already exists" );
                    // break because a newsletter user with email exists
                    return false;
                }
                break;
        }

        $idArray = $subscriptionDataArr['id_array'];
        $listArray = $subscriptionDataArr['list_array'];
        $listOutputFormatArray = $subscriptionDataArr['list_output_format_array'];

        $newsletterUserObject = CjwNewsletterUser::createUpdateNewsletterUser( $email,
                                                                               $salutation,
                                                                               $firstName,
                                                                               $lastName,
                                                                               $eZUserId,
                                                                               (int) $newNewsletterUserStatus,
                                                                               $context );

        $resultArray[ 'newsletter_user_object' ] = $newsletterUserObject;

        if ( is_object( $newsletterUserObject ) === false )
        {
            return $resultArray['errors'] = "Can not create new newsletter user with $email";
        }

        $newsletterUserId = $newsletterUserObject->attribute('id');

        // list_subscribe
        foreach ( $listArray as $listId )
        {
            $outputFormatArray = $listOutputFormatArray[ $listId ];
            $status = CjwNewsletterSubscription::STATUS_PENDING;
            $dryRun = false;
            $resultArray['list_subscribe'][ $listId ] = CjwNewsletterSubscription::createUpdateNewsletterSubscription(
                                                                                                                    $listId,
                                                                                                                    $newsletterUserId,
                                                                                                                    $outputFormatArray,
                                                                                                                    $status,
                                                                                                                    $dryRun,
                                                                                                                    $context );
        }

        if ( $subscribeOnlyMode === false )
        {
            $listRemoveArray =  array_diff( $idArray, $listArray );
            // list_remove by user self
            foreach ( $listRemoveArray as $listId )
            {
                $resultArray['list_remove'][ $listId ] = CjwNewsletterSubscription::removeSubscriptionByNewsletterUserSelf( $listId, $newsletterUserId );
            }
        }
        return $resultArray;
    }

    /**
     * Remove subscription by user self
     *
     * @see newsletter/configure
     *
     * @param integer $listContentObjectId
     * @param integer $newsletterUserId
     * @return object
     */
    static function removeSubscriptionByNewsletterUserSelf( $listContentObjectId, $newsletterUserId )
    {
        $existingSubscriptionObject = CjwNewsletterSubscription::fetchByListIdAndNewsletterUserId( $listContentObjectId, $newsletterUserId );

        if ( is_object( $existingSubscriptionObject ) )
        {
            $existingSubscriptionObject->unsubscribe();
        }
        return $existingSubscriptionObject;
    }

    /**
     * Remove subscription by admin
     *
     * @see newsletter/user_edit
     * @param integer $listContentObjectId
     * @param integer $newsletterUserId
     * @return object
     */
    static function removeSubscriptionByAdmin( $listContentObjectId, $newsletterUserId )
    {
        $existingSubscriptionObject = CjwNewsletterSubscription::fetchByListIdAndNewsletterUserId( $listContentObjectId, $newsletterUserId );

        if ( is_object( $existingSubscriptionObject ) )
        {
            $existingSubscriptionObject->removeByAdmin();
        }
        return $existingSubscriptionObject;
    }



    /*
        create / update subscription
        return newsletter_user_object
    */
    /**
     * create / update subscription
     * return newsletter_user_object
     *
     * @param integer $listContentObjectId
     * @param integer $newsletterUserId
     * @param array $outputFormatArray
     * @param integer $status
     * @param integer $dryRun if true changes will be not stored to db usefull for test runs @see user_edit
     * @return object
     */
    static function createUpdateNewsletterSubscription( $listContentObjectId,
                                                        $newsletterUserId,
                                                        $outputFormatArray,
                                                        $status = CjwNewsletterSubscription::STATUS_PENDING,
                                                        $dryRun = false,
                                                        $context = 'default' )
    {
        $existingSubscriptionObject = CjwNewsletterSubscription::fetchByListIdAndNewsletterUserId( $listContentObjectId, $newsletterUserId );
        $newsletterUser = CjwNewsletterUser::fetch( $newsletterUserId );

        // wenn user confirmed neue subscription auch gleich confirmen
        if ( is_object( $newsletterUser )
            && (int) $newsletterUser->attribute('status') == CjwNewsletterUser::STATUS_CONFIRMED
            && $status == CjwNewsletterSubscription::STATUS_PENDING )
        {
            $status = CjwNewsletterSubscription::STATUS_CONFIRMED;
        }
        // update existing
        if ( is_object( $existingSubscriptionObject ) )
        {
            $existingSubscriptionObject->setAttribute('output_format_array_string', CjwNewsletterSubscription::arrayToString(  $outputFormatArray ) );
            $existingSubscriptionObject->setAttribute('status', $status );
            if ( $dryRun === false )
            {
                $existingSubscriptionObject->store();
            }
            return $existingSubscriptionObject;
        }
        // create new object
        else
        {
            $object = CjwNewsletterSubscription::create( $listContentObjectId,
                                                         $newsletterUserId,
                                                         $outputFormatArray,
                                                         $status,
                                                         $context );
            if ( $dryRun === false )
            {
                $object->store();
            }
            return $object;
        }
    }

    /**
     *
     * @param integer $listContentObjectId
     * @param integer $newsletterUserId
     * @param boolean $asObject
     * @return array / boolean
     */
    static function fetchByListIdAndNewsletterUserId( $listContentObjectId, $newsletterUserId, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList( CjwNewsletterSubscription::definition(),
                                                    null,
                                                    array( 'list_contentobject_id' => $listContentObjectId,
                                                       'newsletter_user_id' => $newsletterUserId ),
                                                    null,
                                                    null,
                                                    $asObject );
        $listCount = count( $objectList);
        if ( $listCount == 1 )
        {
            return $objectList[0];
        }
        else if (  $listCount > 1 )
        {
            // TODO error mehrere Einträge gefunden
            return $objectList[0];
        }
        else
        {
            return false;
        }

    }

    /**
     * Search alle user who subscript to ListId
     *
     * @param integer $listContentObjectId
     * @param mixed int|array $statusIds
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchSubscriptionListByListId( $listContentObjectId, $statusId = false, $limit = 50, $offset = 0, $asObject = true )
    {
        $sortArr = array( 'created' => 'desc' );
        $limitArr = null;

        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $condArr = array( 'list_contentobject_id' => (int) $listContentObjectId );
        if( $statusId !== false )
        {
            if( is_array( $statusId) )
            {
                $condArr['status'] = array( $statusId );
            }
            else
            {
                $condArr['status'] = $statusId;
            }
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    CjwNewsletterSubscription::definition(),
                                                    null,
                                                    $condArr,
                                                    $sortArr,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );
        return $objectList;
    }

    /**
     * Count all user who subscripe to list
     *
     * @param integer $listContentObjectId
     * @param mixed int|array $statusIds
     * @return integer
     */
    static function fetchSubscriptionListByListIdCount( $listContentObjectId, $statusId = false )
    {
        $condArr = array( 'list_contentobject_id' => (int) $listContentObjectId );
        if( $statusId !== false )
        {
            if( is_array( $statusId) )
            {
                $condArr['status'] = array( $statusId );
            }
            else
            {
                $condArr['status'] = $statusId;
            }
        }

        $count = eZPersistentObject::count(
                     self::definition(),
                     $condArr,
                     'id' );
        return $count;
    }

    /**
     * Search all subscription with importId
     *
     * @param integer $importId
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchSubscriptionListByImportId( $importId, $limit = 50, $offset = 0, $asObject = true )
    {
        $sortArr = array( );
        $limitArr = null;

        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    CjwNewsletterSubscription::definition(),
                                                    null,
                                                    array( 'import_id' => (int) $importId ),
                                                    $sortArr,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );
        return $objectList;
    }


    /**
     * Count all user who subscripe to list
     *
     * @param integer $importId
     * @return integer
     */
    static function fetchSubscriptionListByImportIdCount( $importId )
    {
        $count = eZPersistentObject::count(
                     self::definition(),
                     array( 'import_id' => (int) $importId ),
                     'id' );
        return $count;
    }

    /**
     * Count all user who subscripe to list
     *
     * @param integer $importId
     * @param integer Subscirpiton STATUS
     * @return integer
     */
    static function fetchSubscriptionListByImportIdAndStatusCount( $importId, $status )
    {
        $count = eZPersistentObject::count(
                     self::definition(),
                     array( 'import_id' => (int) $importId,
                            'status'    => (int) $status ),
                     'id' );
        return $count;
    }

    /**
     * Search all subscription with importId and subscription status
     *
     * @param integer $importId
     * @param integer $status
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchSubscriptionListByImportIdAndStatus( $importId, $status, $limit = 50, $offset = 0, $asObject = true )
    {
        $sortArr = array( );
        $limitArr = null;

        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    CjwNewsletterSubscription::definition(),
                                                    null,
                                                    array( 'import_id' => (int) $importId,
                                                           'status' => (int) $status   ),
                                                    $sortArr,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );
        return $objectList;
    }


    /**
     * Fetch all subscription by user_id incl. removed subscriptions
     *
     * @param integer $newsletterUserId
     * @param boolean $asObject
     * @return array
     */
    static function fetchSubscriptionListByNewsletterUserId( $newsletterUserId, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterSubscription::definition(),
                        null,
                        array( 'newsletter_user_id' => (int) $newsletterUserId ),
                        null,
                        null,
                        true
                        );
        return $objectList;
    }

    /**
     * Fetch all subscription by user_id without removed / blacklisted subscriptions
     *
     * @param $newsletterUserId
     * @param boolean $asObject
     * @return array
     */
    static function fetchListNotRemovedOrBlacklistedByNewsletterUserId( $newsletterUserId, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterSubscription::definition(),
                        null,
                        array( 'newsletter_user_id' => (int) $newsletterUserId,
                               'status' => array( array( CjwNewsletterSubscription::STATUS_PENDING,
                                                         CjwNewsletterSubscription::STATUS_CONFIRMED,
                                                         CjwNewsletterSubscription::STATUS_APPROVED,
                                                         CjwNewsletterSubscription::STATUS_BOUNCED_SOFT,
                                                         CjwNewsletterSubscription::STATUS_BOUNCED_HARD ) ) ),
                        null,
                        null,
                        true
                        );
        return $objectList;
    }



    /**
     * Count all user who subscripe to list group by status
     *
     * @param integer $listConentObjectId
     * @return array
     */
    static function fetchSubscriptionListStatistic( $listConentObjectId )
    {
        $db = eZDB::instance();
        $query = "SELECT status, COUNT(id) as count
                  FROM cjwnl_subscription
                  WHERE list_contentobject_id=". (int) $listConentObjectId .
                " GROUP BY status";
        $rows = $db->arrayQuery( $query );

        $statistikArray = array(
                                'all'       => 0,
                                'pending'   => 0,
                                'confirmed' => 0,
                                'approved'  => 0,
                                'removed'   => 0,
                                'bounced'   => 0,
                                'blacklisted' => 0 );

        foreach( $rows as $row  )
        {
            $count = $row['count'];

            switch ( (int) $row['status'] )
            {
                case self::STATUS_PENDING:
                    $statistikArray[ 'pending' ] += $count;
                break;

                case self::STATUS_CONFIRMED:
                    $statistikArray[ 'confirmed' ] += $count;
                break;

                case self::STATUS_APPROVED:
                    $statistikArray[ 'approved' ] += $count;
                break;

                case self::STATUS_REMOVED_ADMIN:
                case self::STATUS_REMOVED_SELF:
                    $statistikArray[ 'removed' ] += $count;
                break;

                case self::STATUS_BOUNCED_SOFT:
                case self::STATUS_BOUNCED_HARD:
                    $statistikArray[ 'bounced' ] += $count;
                break;

                case self::STATUS_BLACKLISTED:
                    $statistikArray[ 'blacklisted' ] += $count;
                break;
            }
            $statistikArray[ 'all' ] += $count;
        }
        return $statistikArray;
    }

    /**
     * Fetch by hash
     * subscription_activation by hash
     *
     * @param array $hash
     * @param boolean $asObject
     * @return object
     */
    static function fetchByHash( $hash, $asObject = true )
    {
        return eZPersistentObject::fetchObject( CjwNewsletterSubscription::definition(),
                                                null,
                                                array( 'hash' => $hash ),
                                                $asObject );
    }


    /**
     * Search all subsciptions to a list + status
     *
     * @param integer $listContentObjectId
     * @param integer $status
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchSubscriptionListByListIdAndStatus( $listContentObjectId, $status, $limit = 50, $offset = 0, $asObject = true )
    {
        $sortArr = array( 'created' => 'desc' );
        $limitArr = null;

        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    CjwNewsletterSubscription::definition(),
                                                    null,
                                                    array( 'list_contentobject_id' => (int) $listContentObjectId,
                                                           'status' => (int) $status ),
                                                    $sortArr,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );


        return $objectList;
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
                            CjwNewsletterSubscription::definition(),
                            null,
                            array( 'id' => $id ),
                            true
                            );

        return $object;
    }

    /**
     * set modified to current timestamp and set current User Id
     * if first version use created as modified timestamp
     */
    public function setModified()
    {
        if( $this->attribute('id') > 1 )
        {
            $this->setAttribute( 'modified', time() );
            $this->setAttribute( 'modifier_contentobject_id', eZUser::currentUserID() );
        }
        // first version created = modified
        else
        {
            $this->setAttribute( 'modified', $this->attribute( 'created' ) );
            $this->setAttribute( 'modifier_contentobject_id', eZUser::currentUserID() );
        }
    }

    /**
     * set Modifed data if somebody store content
     * (non-PHPdoc)
     * @see kernel/classes/eZPersistentObject#store($fieldFilters)
    */
    public function store( $fieldFilters = null )
    {
        $this->setModified();
        parent::store( $fieldFilters );
    }

    /**
     * remove the current subscription
     * @see kernel/classes/eZPersistentObject#remove($conditions, $extraConditions)
     */
    function remove( $conditions = null, $extraConditions = null )
    {

        CjwNewsletterLog::writeNotice(
                                        'CjwNewsletterSubscription::remove',
                                        'subscription',
                                        'remove',
                                         array( 'nl_user' => $this->attribute( 'newsletter_user_id' ),
                                                'subscription_id' => $this->attribute( 'id' ),
                                                'modifier' => eZUser::currentUserID() )
                                          );

        foreach( $currentNewsletterSubscriptionObjects as $subscription )
        {
            $subscription->remove();
        }
        parent::remove( $conditions, $extraConditions );
    }



}

?>
