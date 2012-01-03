<?php
/**
 * File containing the CjwNewsletterUser class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Data management datatyp cjwnewsletterlist
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */

require_once( 'kernel/common/i18n.php' );

class CjwNewsletterUser extends eZPersistentObject
{
    /**
     *
     * @var int if newsletter user has this status he wants do get newsletter but did activate his ez user account
     */
    const STATUS_PENDING_EZ_USER_REGISTER = 20;
    /**
     *
     * @var int if newsletter user has this status he wants do get newsletter but did not confirm his email
     */
    const STATUS_PENDING = 0;
    /**
     *
     * @var int if newsletter user has this status he can get newsletter mails
     */
    const STATUS_CONFIRMED = 1;

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

    /**
     * Initializes a new GeoadressData alias
     *
     * @param unknown_type $row
     * @return void
     */
    function CjwNewsletterUser( $row )
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

                                         'email' => array( 'name' => 'Email',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'salutation' => array( 'name' => 'Salutation',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => false ),
                                         'first_name' => array( 'name' => 'FirstName',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),
                                         'last_name' => array( 'name' => 'LastName',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),
                                         'organisation' => array( 'name' => 'Organisation',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),
                                         'birthday' => array( 'name' => 'Birthday',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),
                                         'data_xml' => array( 'name' => 'AdditionalData',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),
                                         'hash' => array( 'name' => 'Hash',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'ez_user_id' => array( 'name' => 'EzUserId',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => false ),
                                         'creator_contentobject_id' => array( 'name' => 'CreatorContentobjectId',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'modifier_contentobject_id' => array( 'name' => 'ModifierContentobjectId',
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
                                         'removed' => array( 'name' => 'Removed',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'bounced' => array( 'name' => 'Bounced',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'blacklisted' => array( 'name' => 'Blacklisted',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'note' => array( 'name' => 'Note',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),
                                         'remote_id' => array( 'name' => 'RemoteId',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),
                                         'import_id' => array( 'name' => 'ImportId',
                                                                'datatype' => 'integer',
                                                                'default' => '',
                                                                'required' => false ),
                                         'bounce_count' => array( 'name' => 'BounceCount',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'data_text' => array( 'name' => 'DateText',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),

                                                                    ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',

                      'function_attributes' => array( 'name' => 'getName',
                                                      'salutation_name' => 'getSalutationName',
                                                      'is_confirmed' => 'isConfirmed',
                                                      'is_removed_self' => 'isRemovedSelf',
                                                      'subscription_array' => 'getSubscriptionArray',
                                                      'email_name' =>  'getEmailName',
                                                      'creator' => 'getCreatorUserObject',
                                                      'modifier' => 'getModifierUserObject',
                                                      'ez_user' => 'getEzUserObject',
                                                      'status_string' => 'getStatusString',
                                                      'available_salutation_name_array' => 'getAvailableSalutationNameArray'
                                                                    ),
                      'class_name' => 'CjwNewsletterUser',
                      'name' => 'cjwnl_user' );
    }

    /**
     * Create new CjwNewsletterUser object
     *
     * @param string $email
     * @param string $salutation
     * @param string $firstName
     * @param string $lastName
     * @param string $eZUserId
     * @param int $status
     * @return object
     */
    static function create( $email, $salutation, $firstName, $lastName, $eZUserId, $status = CjwNewsletterUser::STATUS_PENDING, $context = 'default' )
    {
        $rows = array( 'created' => time(),
                       'creator_contentobject_id' => eZUser::currentUserID(),
                       'ez_user_id' => $eZUserId,
                       'email' => $email,
                       'first_name' => $firstName,
                       'last_name' => $lastName,
                       'salutation' => $salutation,
                       'hash' => CjwNewsletterUtils::generateUniqueMd5Hash( $email ),
                       'remote_id' => 'cjwnl:'. $context .':' . CjwNewsletterUtils::generateUniqueMd5Hash( $email ),
                       'status' => $status );

        $object = new CjwNewsletterUser( $rows );
        return $object;
    }



  /**
   * Create or update Newsletter User identified by email
   * store the changes to Database
   *
   * @param string $email
   * @param int $salutation
   * @param string $firstName
   * @param string $lastName
   * @param int $eZUserId
   * @param int $newNewsletterUserStatus the status for new created Newsletter users CjwNewsletterUser::STATUS_PENDING
   * @return object
   */
    static function createUpdateNewsletterUser( $email,
                                                $salutation,
                                                $firstName,
                                                $lastName,
                                                $eZUserId,
                                                $newNewsletterUserStatus = CjwNewsletterUser::STATUS_PENDING,
                                                $context = 'default' )
    {
        /*
         * 1. exist a current newsletter user?
         *      yes -> register on lists with status PENDING
         *      no -> create new user with status PENDIG and than register
         */
        $existingNewsletterUserObject = CjwNewsletterUser::fetchByEmail( $email );

        // update existing
        if ( is_object( $existingNewsletterUserObject ) )
        {
            $userObject = $existingNewsletterUserObject;
            $userObject->setAttribute('salutation', $salutation );
            $userObject->setAttribute('first_name', $firstName );
            $userObject->setAttribute('last_name', $lastName );
            $userObject->setAttribute('ez_user_id', (int) $eZUserId );
            $userObject->setAttribute('modified', time() );
            $userObject->store();
            CjwNewsletterLog::writeDebug(
                                    'CjwNewsletterUser::createUpdateNewsletterUser',
                                    'user',
                                    'update',
                                     array( 'nl_user' => $userObject->attribute( 'id' ),
                                            'email' => $email,
                                            'salutation' => $salutation,
                                            'first_name' => $firstName,
                                            'last_name' => $lastName,
                                            'ez_user_id' => $userObject->attribute( 'ez_user_id' ),
                                            'status' => $userObject->attribute( 'status' ),
                                            'modifier' => eZUser::currentUserID(),
                                            'context' => $context )
                                      );
        }
        // create new object
        else
        {
            $userObject = CjwNewsletterUser::create( $email,
                                                     $salutation,
                                                     $firstName,
                                                     $lastName,
                                                     $eZUserId,
                                                     (int) $newNewsletterUserStatus,
                                                     $context );
            if( is_object( $userObject ) !== true )
            {
                // error creating the new user => user with same email already exists
                return false;
            }

            $userObject->store();
            CjwNewsletterLog::writeDebug(
                                    'CjwNewsletterUser::createUpdateNewsletterUser',
                                    'user',
                                    'create',
                                     array( 'nl_user' => $userObject->attribute( 'id' ),
                                            'email' => $email,
                                            'salutation' => $salutation,
                                            'first_name' => $firstName,
                                            'last_name' => $lastName,
                                            'ez_user_id' => $userObject->attribute( 'ez_user_id' ),
                                            'status' => $userObject->attribute( 'status' ),
                                            'modifier' => eZUser::currentUserID(),
                                            'context' => $context )
                                      );

        }
        return $userObject;
    }

    /**
     * search the ez_user_id for the current nl email
     * @return int $ezUserId / false
     */
    public function findAndSetRelatedEzUserId()
    {
        $currentEzUserId = $this->attribute( 'ez_user_id' );
        // if not set
        if( $currentEzUserId == 0 )
        {
            $email = $this->attribute( 'email' );
            if( $email != '' )
            {
                $existingEzUser = eZUser::fetchByEmail( $email );
                if( is_object( $existingEzUser ) )
                {
                    $ezUserId = $existingEzUser->attribute( 'contentobject_id' );
                    $this->setAttribute( 'ez_user_id', $ezUserId );
                    return $ezUserId;
                }
            }
        }
        else
        {
            return $currentEzUserId;
        }

        return false;
    }

    /**
     *
     * @see kernel/classes/eZPersistentObject#setAttribute($attr, $val)
     */
    function setAttribute( $attr, $value )
    {
        // TODO check if modified should be update every time a attribute is set
        // may be in store method better place to do this
        switch ( $attr )
        {
            case 'email':
            {
                $currentEmail = $this->attribute( 'email' );
                $newEmail = $value;
                if ( $currentEmail != $newEmail )
                {
                    CjwNewsletterLog::writeNotice(
                                        'set new email CjwNewsletterUser::setAttribute',
                                        'user',
                                        'email',
                                         array( 'nl_user' => $this->attribute( 'id' ),
                                                'email_old' => $currentEmail,
                                                'email_new' => $newEmail,
                                                'status' => $this->attribute( 'status' ),
                                                'modifier' => eZUser::currentUserID() )
                                          );
                }
                return eZPersistentObject::setAttribute( $attr, $value );
            } break;
            case 'status':
            {
                $currentTimeStamp = time();
                switch ( $value )
                {
                    case CjwNewsletterUser::STATUS_CONFIRMED :
                    {
                        $this->setAttribute( 'confirmed', $currentTimeStamp );
                        // if a user is confirmed reset bounce count
                        $this->resetBounceCount();

                    } break;

                    case CjwNewsletterUser::STATUS_BOUNCED_SOFT :
                    case CjwNewsletterUser::STATUS_BOUNCED_HARD :
                    {
                        $this->setAttribute( 'bounced', $currentTimeStamp );
                        // set all subscriptions and all open senditems to bounced
                        // see
                        // setBounced
                        // setAllNewsletterUserRelatedItemsToStatus
                    } break;
                    case CjwNewsletterUser::STATUS_REMOVED_ADMIN :
                    case CjwNewsletterUser::STATUS_REMOVED_SELF :
                    {
                        $this->setAttribute( 'removed', $currentTimeStamp );
                        // set all subscriptions and all open senditems to removed
                        //
                    } break;
                    case CjwNewsletterUser::STATUS_BLACKLISTED :
                    {
                        $this->setAttribute( 'blacklisted', $currentTimeStamp );
                        // set all subscriptions and all open senditems to blacklisted
                        // see
                        // setBlacklisted
                        // setAllNewsletterUserRelatedItemsToStatus
                    } break;
                }

                $statusOld = $this->attribute( 'status' );
                $statusNew = $value;

                if( $statusOld != $statusNew )
                {
                    CjwNewsletterLog::writeNotice(
                                        'set CjwNewsletterUser::setAttribute',
                                        'user',
                                        'status',
                                         array( 'nl_user' => $this->attribute( 'id' ),
                                                'status_old' => $statusOld,
                                                'status_new' => $statusNew,
                                                'modifier' => eZUser::currentUserID() )
                                          );
                }
                else
                {
                    CjwNewsletterLog::writeDebug(
                                        'set CjwNewsletterUser::setAttribute',
                                        'user',
                                        'status',
                                         array( 'nl_user' => $this->attribute( 'id' ),
                                                'status_old' => $statusOld,
                                                'status_new' => $statusNew,
                                                'modifier' => eZUser::currentUserID() )
                                          );
                }
                return eZPersistentObject::setAttribute( $attr, $value );

            } break;
            default:
                return eZPersistentObject::setAttribute( $attr, $value );
        }

    }

    /**
     * set bounce_count to 0
     */
    public function resetBounceCount()
    {
        $this->setAttribute( 'bounce_count' , 0 );
    }

    /**
     * Check if current object has status confirmed
     *
     * @return boolean
     */
    function isConfirmed()
    {
        $status = $this->attribute('status');
        if ( $status == CjwNewsletterUser::STATUS_CONFIRMED )
            return true;
        else
            return false;
    }

    /**
     * Check if current object has status blackisted
     *
     * @return boolean
     */
    function isRemovedSelf()
    {
        $status = $this->attribute('status');
        if ( $status == CjwNewsletterUser::STATUS_REMOVED_SELF )
            return true;
        else
            return false;
    }

    /**
     * Check if current user object is on blacklist
     * and if status is blacklisted
     *
     * @return boolean
     */
    function isOnBlacklist()
    {
        $status = $this->attribute('status');
        $isOnBlacklist = CjwNewsletterBlacklistItem::isEmailOnBlacklist( $this->attribute( 'email' ) );
        if( $isOnBlacklist )
        {
            // fix up status blacklisted if it is not set
            if( $status != CjwNewsletterUser::STATUS_BLACKLISTED )
            {
                $this->setBlacklisted();
                return true;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Send confirmation email for the user is not confirmed
     *
     * @return unknown_type
     */
    function sendSubcriptionConfirmationMail()
    {
        return $this->sendSubcriptionMail( 'design:newsletter/mail/subscription_confirmation.tpl' );
    }

    /**
     * Send newsletter inromation email for the user is already exists
     *
     * @return unknown_type
     */
    function sendSubcriptionInformationMail()
    {
        return $this->sendSubcriptionMail( 'design:newsletter/mail/subscription_information.tpl' );
    }

    /**
     * This function is only a helpfunction
     *
     * @param $mailTemplate
     * @return unknown_type
     */
    function sendSubcriptionMail( $mailTemplate )
    {
        $tplResource = eZTemplateDesignResource::instance();
        $ini = eZINI::instance( 'site.ini' );
        $cjwNewsletterIni = eZINI::instance( 'cjw_newsletter.ini' );
        $hostName = eZSys::hostname();

        // $template = 'design:newsletter/mail/subscription_confirmation.tpl';
        $template = $mailTemplate;
        $newsletterUser = $this;
        include_once( 'kernel/common/template.php' );
        $tpl = templateInit();
        $tpl->setVariable( 'newsletter_user', $newsletterUser );
        $tpl->setVariable( 'hostname', $hostName );
        $templateResult = $tpl->fetch( $template ) ;

        // get subject from template var definition
        if ( $tpl->hasVariable( 'subject' ) )
            $subject = $tpl->variable( 'subject' );

        $emailSender = $cjwNewsletterIni->variable( 'NewsletterMailSettings', 'EmailSender' );
        $emailSenderName = $cjwNewsletterIni->variable( 'NewsletterMailSettings', 'EmailSenderName' );
        $emailReceiver = $newsletterUser->attribute('email');

        // TODO Namen extrahieren
        $emailReceiverName = '';

        $emailSubject = $subject;
        if( $tpl->hasVariable( 'content_type' ) && preg_match( '/^text\/html/', $tpl->variable( 'content_type' ) ) )
        {
            $emailBody['html'] = $templateResult;
        }
        else
        {
            $emailBody['text'] = $templateResult;
        }

        $cjwMail = new CjwNewsletterMail();
        // x header set for current user
        $cjwMail->setExtraMailHeadersByNewsletterUser( $this );
        $cjwMail->setTransportMethodDirectlyFromIni();

        // trigger_error("test error", E_USER_ERROR);
        $sendResult = $cjwMail->sendEmail( $emailSender,
                                          $emailSenderName,
                                          $emailReceiver,
                                          $emailReceiverName,
                                          $emailSubject,
                                          $emailBody );
        return $sendResult;
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
            case self::STATUS_PENDING_EZ_USER_REGISTER:
                $statusString = ezi18n( 'cjw_newsletter/user/status', 'Pending eZ User Register' );
                break;
            case self::STATUS_PENDING:
                $statusString = ezi18n( 'cjw_newsletter/user/status', 'Pending' );
                break;
            case self::STATUS_CONFIRMED:
                $statusString = ezi18n( 'cjw_newsletter/user/status', 'Confirmed' );
                break;
            case self::STATUS_REMOVED_SELF:
                $statusString = ezi18n( 'cjw_newsletter/user/status', 'Removed by user' );
                break;
            case self::STATUS_REMOVED_ADMIN:
                $statusString = ezi18n( 'cjw_newsletter/user/status', 'Removed by admin' );
                break;
            case self::STATUS_BOUNCED_SOFT:
                $statusString = ezi18n( 'cjw_newsletter/user/status', 'Bounced soft' );
                break;
            case self::STATUS_BOUNCED_HARD:
                $statusString = ezi18n( 'cjw_newsletter/user/status', 'Bounced hard' );
                break;
            case self::STATUS_BLACKLISTED:
                $statusString = ezi18n( 'cjw_newsletter/user/status', 'Blacklisted' );
                break;
        }
        return $statusString;
    }

    /**
     * Returns all subcriptions for the current user, which hasn't a REMOVE status
     *
     * @return array
     */
    function getSubscriptionArray()
    {
        $listSubscriptionArray = array();
        $subscriptionArray = CjwNewsletterSubscription::fetchSubscriptionListByNewsletterUserId( $this->attribute('id') );
        foreach ( $subscriptionArray as $subscriptionObject )
        {
            $subscriptionStatus = $subscriptionObject->attribute('status');
            $listSubscriptionArray[ $subscriptionObject->attribute( 'list_contentobject_id') ] = $subscriptionObject;
        }
        return $listSubscriptionArray;
    }

    /**
     * Return the name which will display in email  e.g. Max Mustermman
     *
     * @return string
     */
    function getEmailName( )
    {
        $emailName = '' ;
        $firstName = $this->attribute('first_name');
        $lastName = $this->attribute('last_name');

        if ( $firstName != '' )
            $emailName .= $firstName .' ';

        if ( $lastName != '' )
            $emailName .= $lastName;

        return $emailName;
    }

    /**
     * 1. Set user on status CONFIRMED
     * 2. Set all opened subcriptions on status CONFIRMED
     *
     * @return array
     */
    function confirmAll()
    {
        // user status
        // only the first time set nl user confirmed timestamp
        if ( $this->attribute('confirmed') == 0 )
        {
            $this->setAttribute( 'status', CjwNewsletterUser::STATUS_CONFIRMED );
        }
        // all subscriptions
        $subscriptionArray = $this->attribute('subscription_array');
        $confirmResultArray = array();
        foreach ( $subscriptionArray as $subscriptionObject )
        {
            $confirmResultArray[] = array( 'confirm_result' => $subscriptionObject->confirm(),
                                            'subscription_object' => $subscriptionObject );
        }
        $this->store();
        return $confirmResultArray;
    }

    // fetch functions
    // ######################################



    /**
     * Fetch by hash
     *
     * @param array $hash
     * @param boolean $asObject
     * @return object
     */
    static function fetchByHash( $hash, $asObject = true )
    {
        return eZPersistentObject::fetchObject( CjwNewsletterUser::definition(),
                                                null,
                                                array( 'hash' => $hash ),
                                                $asObject );
    }

    /**
     * Used in datatype cjwnewsletter_list
     *
     * @param string $email
     * @return array / boolean
     */
    static function fetchByEmail( $email )
    {
        $db = eZDB::instance();
        $objectList = eZPersistentObject::fetchObjectList(
                        CjwNewsletterUser::definition(),
                        null,
                        array( 'email' => $db->escapeString( $email ) ),
                        null,
                        null,
                        true
                        );

        $count = count( $objectList );
        if ( $count == 1 )
        {
            return $objectList[0];
        }
        elseif ( $count > 1 )
        {
            $userIdArray = array();
            foreach( $objectList as $nlUser )
            {
                $userIdArray[] = $nlUser->attribute( 'id' );
            }

            CjwNewsletterLog::writeError(
                                    'email existing more than 1 time CjwNewsletterUser::fetchByEmail',
                                    'user',
                                    'email',
                                     array(
                                            'email' => $objectList[0]->attribute( 'email' ),
                                            'email_count' => $count,
                                            'nl_user_ids' => implode( ',', $userIdArray ),
                                            'modifier' => eZUser::currentUserID() )
                                      );
            return $objectList;
        }
        else
        {
            return false;
        }
    }

    /**
     * fetch NewsletterUser Object by eZ User Id
     *
     * @param int $ezUserId
     * @return NewsletterUser / boolean
     */
    static function fetchByEzUserId( $ezUserId )
    {
        if( $ezUserId > 0 )
        {

            $db = eZDB::instance();
            $objectList = eZPersistentObject::fetchObjectList(
                            CjwNewsletterUser::definition(),
                            null,
                            array( 'ez_user_id' => $db->escapeString( $ezUserId ) ),
                            null,
                            null,
                            true
                            );

            if ( count( $objectList ) > 0 )
                return $objectList[0];
            else
                return false;
        }
        else
        {
            return false;
        }
    }


    /**
     * Returns userobject by id
     *
     * @param integer $id
     * @return object
     */
    static function fetch( $id )
    {
        $object = eZPersistentObject::fetchObject(
                            CjwNewsletterUser::definition(),
                            null,
                            array( 'id' => (int) $id ),
                            true
                            );
        return $object;
    }

    /**
     * user search
     *
     * @param integer $limit
     * @param integer $offset
     * @param boolean $emailSearch
     * @param boolean $sortBy
     * @param boolean $asObject
     * @return array
     */
    public static function fetchList( $limit, $offset, $emailSearch = false, $sortBy = false, $asObject = true )
    {
        if ( $sortBy == false && !is_array( $sortBy ))
        {
            $sortArr = array( 'created' => 'desc' );
        }
        else
        {
            $sortArr = $sortBy;
        }
        $limitArr = null;
        $customConds = null;
        if ( $emailSearch != '' && $emailSearch != false )
        {
            $db = eZDB::instance();
            $email = $db->escapeString( $emailSearch );
            $customConds = " WHERE email like \"%$email%\"";
        }

        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }
        $objectList = eZPersistentObject::fetchObjectList(
                                                    self::definition(),
                                                    null,
                                                    array( ),
                                                    $sortArr,
                                                    $limitArr,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    $customConds );
        return $objectList;
    }

    /**
     * user search count
     *
     * @param boolean $emailSearch
     * @return integer
     */
    public static function fetchListCount( $emailSearch = false )
    {
        $db = eZDB::instance();
        $customConds = null;
        if ( $emailSearch != '' && $emailSearch != false )
        {
            $email = $db->escapeString( $emailSearch );
            $customConds = " WHERE email like \"%$email%\"";
        }
        $query = 'SELECT COUNT(id) AS count FROM cjwnl_user' .$customConds;
        $rows = $db -> arrayQuery( $query );
        return $rows[0]['count'];
    }

    /**
     * Fetch all Newsletter user with status
     *
     * @param integer $status
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array with CjwNewsletterUser objects
     */
    static function fetchUserListByStatus( $status, $limit = 50, $offset = 0, $asObject = true )
    {
        $sortArr = null;
        $limitArr = null;

        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    self::definition(),
                                                    null,
                                                    array( 'status' => (int) $status ),
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
     * Search all user with importId
     *
     * @param integer $importId
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return array
     */
    static function fetchUserListByImportId( $importId, $limit = 50, $offset = 0, $asObject = true )
    {
        $sortArr = array( );
        $limitArr = null;

        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        $objectList = eZPersistentObject::fetchObjectList(
                                                    self::definition(),
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
     * Count all user who subscripe to list with importId
     *
     * @param integer $importId
     * @return integer
     */
    static function fetchUserListByImportIdCount( $importId )
    {
        $count = eZPersistentObject::count(
                     self::definition(),
                     array( 'import_id' => (int) $importId ),
                     'id' );
        return $count;
    }
     /**
     * Count all user who subscripe to list with import id and status id
     *
     * @param integer $importId
     * @return integer
     */
    static function fetchUserListByImportIdAndStatusCount( $importId, $status )
    {
        $count = eZPersistentObject::count(
                     self::definition(),
                     array( 'import_id' => (int) $importId,
                            'status'    => (int) $status ),
                     'id' );
        return $count;
    }


    /**
     * Get user object
     *
     * @return eZUser object
     */
    function getEzUserObject()
    {
        $retVal = false;
        if ( $this->attribute( 'ez_user_id' ) != 0 )
        {
            $retVal = eZUser::fetch( $this->attribute( 'ez_user_id' ) );
        }
        return $retVal;
    }

    /**
     * Get conteobject for userId
     *
     * @return eZContentObject object
     */
    function getEzUserContentObject()
    {
        $retVal = false;
        if ( $this->attribute( 'ez_user_id' ) != 0 )
        {
            $retVal = eZContentObject::fetch( $this->attribute( 'ez_user_id' ) );
        }
        return $retVal;
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
        if ( $this->attribute( 'creator_contentobject_id' ) != 0 )
        {
            $user = eZContentObject::fetch( $this->attribute( 'creator_contentobject_id' ) );
            return $user;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get Name of NL User
     * use a tpl to have full flexebiltiy to render the name
     *
     * @return string
     */
    function getName()
    {
        $cjwNewsletterIni = eZINI::instance( 'cjw_newsletter.ini' );
        $useTplForNameGeneration = $cjwNewsletterIni->variable( 'NewsletterUserSettings', 'UseTplForNameGeneration' );
        if( $useTplForNameGeneration === 'enabled' )
        {
            include_once( 'kernel/common/template.php' );
            $tpl = templateInit();
            $tpl->setVariable( 'nl_user', $this );
            $templateFile = 'design:newsletter/user/name.tpl';
            $name = strip_tags( trim( $tpl->fetch( $templateFile ) ) );
            unset( $tpl );
            return $name;
        }
        else
        {
            $name = trim( $this->attribute( 'salutation_name'). ' ' . $this->attribute( 'first_name' ) . ' ' . $this->attribute( 'last_name' ) );
            return $name;
        }
    }

    /**
     * Get i18n for salutation id
     * user cjw_newsletter.ini
     * [NewsletterUserSettings]
     * SalutationMappingArray[value_1]=Mr.
     * SalutationMappingArray[value_2]=Mrs.
     *
     * so we can extent this
     * @return string
     */
    function getSalutationName()
    {
        $availableSalutationNameArray = self::getAvailableSalutationNameArrayFromIni();
        $salutationId = (int) $this->attribute('salutation');
        if ( array_key_exists( $salutationId, $availableSalutationNameArray ) )
        {
            return $availableSalutationNameArray[ $salutationId ];
        }
        else
        {
            return '';
        }
    }

    /**
     * attribute( 'available_salutation_name_array' )
     * @return array[salutation_id]=>i18n
     */
    public function getAvailableSalutationNameArray()
    {
        return CjwNewsletterUser::getAvailableSalutationNameArrayFromIni();
    }
    /**
     *
     * @return array[salutation_id]=>i18n
     */
    static function getAvailableSalutationNameArrayFromIni()
    {
        $cjwNewsletterIni = eZINI::instance( 'cjw_newsletter.ini' );
        $salutationMappingArray = $cjwNewsletterIni->variable( 'NewsletterUserSettings', 'SalutationMappingArray' );
        $salutationNameArray = array();
        foreach( $salutationMappingArray as $salutationKey => $languageString )
        {
            // value_1
            $salutationKeyExplode = explode( '_', $salutationKey );
            if( isSet( $salutationKeyExplode[1] ))
            {
                $salutationId = (int) $salutationKeyExplode[1];
                $salutationNameArray[ $salutationId ] = ezi18n( 'cjw_newsletter/user/salutation', $languageString );
            }
        }
        return $salutationNameArray;
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
        // find and set ez_user_id
        $this->findAndSetRelatedEzUserId();
        parent::store( $fieldFilters );
    }

    /**
     * remove the current newlsetter user and all depending nl subscriptions
     * @see kernel/classes/eZPersistentObject#remove($conditions, $extraConditions)
     */
    function remove( $conditions = null, $extraConditions = null )
    {
        // remove subscriptions
        $currentNewsletterSubscriptionObjects = $this->attribute( 'subscription_array' );

        CjwNewsletterLog::writeNotice(
                                        'CjwNewsletterUser::remove',
                                        'user',
                                        'remove',
                                         array( 'nl_user' => $this->attribute( 'id' ),
                                                'subscription_count' => count( $currentNewsletterSubscriptionObjects ),
                                                'subscriptions_to_remove' => implode( '|', array_keys( $currentNewsletterSubscriptionObjects  ) ),
                                                'modifier' => eZUser::currentUserID() )
                                               );

        foreach( $currentNewsletterSubscriptionObjects as $subscription )
        {
            $subscription->remove();
        }
        $blackListItem = CjwNewsletterBlacklistItem::fetchByEmail( $this->attribute( 'email' ) );
        if ( is_object( $blackListItem ) )
        {
            $blackListItem->setAttribute( 'newsletter_user_id', 0 );
            $blackListItem->store();
        }
        parent::remove( $conditions, $extraConditions );
    }

    /**
     * set current object blacklisted
     * Called from CJWNewsletterBackListItem::store()
     * @return void
     */
    public function setBlacklisted()
    {
        CjwNewsletterLog::writeDebug(
                                    'CjwNewsletterUser::setBlacklisted',
                                    'user',
                                    'blacklist',
                                     array( 'nl_user' => $this->attribute( 'id' ) )
                                     );

        $this->setAttribute( 'status', self::STATUS_BLACKLISTED );

        // set all subscriptions and all open senditems to blacklisted
        $this->setAllNewsletterUserRelatedItemsToStatus( CjwNewsletterSubscription::STATUS_BLACKLISTED );

        $this->store();
    }

    /**
     * Set current object non-blacklisted
     * User and subscriptions will be set to confirmed
     * @return void
     */
    public function setNonBlacklisted()
    {
        CjwNewsletterLog::writeDebug(
                                    'CjwNewsletterUser::setNonBlacklisted',
                                    'user',
                                    'blacklist',
                                     array( 'nl_user' => $this->attribute( 'id' ) )
                                     );

        // we determine the actual status by checking the various timestamps
        if ( $this->attribute( 'confirmed' ) != 0 )
        {
            if ( $this->attribute( 'bounced' ) != 0 || $this->attribute( 'removed' ) != 0 )
            {
                if ( $this->attribute( 'removed' ) > $this->attribute( 'bounced' ) )
                    $this->setRemoved();
                else
                    $this->setBounced();
            }
            // confirmed, and not deleted nor bounced
            else
                $this->setAttribute( 'status', self::STATUS_CONFIRMED );
        }
        // not confirmed
        else
        {
            // might have been removed by admin
            if ( $this->attribute( 'removed' ) != 0 )
                $this->setRemoved( true );
            else
                $this->setAttribute( 'status', self::STATUS_PENDING );
        }
        $this->setAttribute( 'blacklisted', 0 );

        // set all subscriptions and all open senditems to blacklisted
        foreach( CjwNewsletterSubscription::fetchSubscriptionListByNewsletterUserId( $this->attribute( 'id' ) )
            as $subscription )
        {
            $subscription->setNonBlacklisted();
        }

        $this->store();
    }

    /**
     * call this function if a bounce mail for current user is detected
     * if it is a hard bounce set
     * @param boolean $isHardBounce
     * @return unknown_type
     */
    public function setBounced( $isHardBounce = false )
    {
        $cjwNewsletterIni = eZINI::instance( 'cjw_newsletter.ini' );
        $bounceThresholdValue = (int) $cjwNewsletterIni->variable( 'BounceSettings', 'BounceThresholdValue' );
        $userBouncCount = $this->attribute('bounce_count') + 1;
        $this->setAttribute( 'bounce_count', $userBouncCount );

        CjwNewsletterLog::writeDebug(
                                    'CjwNewsletterUser::setBounced',
                                    'user',
                                    'bounce_count',
                                     array( 'nl_user' => $this->attribute( 'id' ),
                                            'bounce_count' => $userBouncCount ) );


        // set all subscriptions and all open senditems to bounced
        if( $userBouncCount >= $bounceThresholdValue )
        {
            if( $isHardBounce === true )
            {
                $this->setAttribute( 'status', self::STATUS_BOUNCED_HARD );
                $this->setAllNewsletterUserRelatedItemsToStatus( self::STATUS_BOUNCED_HARD );
            }
            else
            {
                $this->setAttribute( 'status', self::STATUS_BOUNCED_SOFT );
                $this->setAllNewsletterUserRelatedItemsToStatus( self::STATUS_BOUNCED_SOFT );
            }
        }
        $this->store();
    }

    /**
     * call this function if a bounce mail for current user is detected
     * if it is a hard bounce set
     * @param boolean $isHardBounce
     * @return unknown_type
     */
    public function setRemoved( $byAdmin = false )
    {
        CjwNewsletterLog::writeDebug(
                                    'CjwNewsletterUser::setRemoved',
                                    'user',
                                    'bounce_count',
                                     array( 'nl_user' => $this->attribute( 'id' ),
                                            'by_admin' => ( $byAdmin === true ? 1 : 0 ) ) );

        if( $byAdmin === true )
        {
            $this->setAttribute( 'status', self::STATUS_REMOVED_ADMIN );
            $this->setAllNewsletterUserRelatedItemsToStatus( self::STATUS_REMOVED_ADMIN );
        }
        else
        {
            $this->setAttribute( 'status', self::STATUS_REMOVED_SELF );
            $this->setAllNewsletterUserRelatedItemsToStatus( self::STATUS_REMOVED_SELF );
        }
    }


    /**
     * This should be called if a user is bounced or blacklisted
     * all related subscriptions and active senditems will be updated
     *
     * @param $status
     * @return unknown_type
     */
    private function setAllNewsletterUserRelatedItemsToStatus( $status )
    {
        $newsletterUserId = $this->attribute('id');

        switch ( $status )
        {
            case CjwNewsletterSubscription::STATUS_BOUNCED_SOFT:
            case CjwNewsletterSubscription::STATUS_BOUNCED_HARD:
            case CjwNewsletterSubscription::STATUS_BLACKLISTED:

                // update active senditems
                $abortCount = CjwNewsletterEditionSendItem::setAllActiveItemsToStatusAbortByNewsletterUserId( $newsletterUserId );

                // update active subscriptions
                $activeSubscriptionList = CjwNewsletterSubscription::fetchListNotRemovedOrBlacklistedByNewsletterUserId( $newsletterUserId, true );
                foreach ( $activeSubscriptionList as $subscription )
                {
                    if( $subscription->attribute( 'status') == $status )
                    {
                        CjwNewsletterLog::writeDebug(
                                                    'skip - already set this status - CjwNewsletterUser::setAllNewsletterUserRelatedItemsToStatus',
                                                    'subscription',
                                                    'status',
                                                     array( 'status' => $status,
                                                            'subscription_id' => $subscription->attribute('id'),
                                                            'nl_user' => $newsletterUserId ) );
                    }
                    else
                    {
                        $subscription->setAttribute( 'status', $status );
                        $subscription->store();
                    /*    CjwNewsletterLog::writeDebug(
                                                    'set CjwNewsletterUser::setAllNewsletterUserRelatedItemsToStatus',
                                                    'subscription',
                                                    'status',
                                                     array( 'status' => $status,
                                                            'subscription_id' => $subscription->attribute('id'),
                                                            'nl_user' => $newsletterUserId ) );
                                                            */
                    }
                }
            break;
        }
    }

    /**
     * check if $email and $ezUserId can update/create
     * negative value fail
     * @see CjwNewsletterSubscription::createSubscriptionByArray
     */
    public static function checkIfUserCanBeUpdated( $email, $ezUserId, $updateNewEmail = false )
    {

        // TODO cache fetches
        // check if new email exists in the system
        $idNlUser = CjwNewsletterUser::fetchByEzUserId( $ezUserId );
        $emailNlUser = CjwNewsletterUser::fetchByEmail( $email );

        $idNlUserEmail = false;
        $idNlUserEzUserId = 0;
        $idNlUserId = 0;
        $idNlUserExists = false;

        $emailNlUserEmail = false;
        $emailNlUserEzUserId = 0;
        $emailNlUserId = 0;
        $emailNlUserExists = false;

        if ( is_object( $idNlUser ) )
        {
            $idNlUserEmail = strtolower( trim( $idNlUser->attribute( 'email' ) ) );
            $idNlUserEzUserId = $idNlUser->attribute( 'ez_user_id' );
            $idNlUserId = $idNlUser->attribute( 'id' );
            if( $idNlUserEzUserId > 0 )
                $idNlUserExists = true;
        }
        else
        {
            if( $ezUserId > 0 )
            {
                $idNlUserEzUserId = $ezUserId;
            }
        }

        if ( is_object( $emailNlUser ) )
        {
            $emailNlUserEmail = strtolower( trim( $emailNlUser->attribute( 'email' ) ) );
            $emailNlUserEzUserId = $emailNlUser->attribute( 'ez_user_id' );
            $emailNlUserId = $emailNlUser->attribute( 'id' );
            $emailNlUserExists = true;
        }
        else
        {
            $emailNlUserEmail = $email;
        }

        // new user => email + ezUserId not found in any nl user objects  => 40
        // update user => ezUserId == 0 or found + email not found + email1 == email2 => 41
        // update user with new email => ez_user_id found + email1 != email2 => 42

        $returnStatus = -1;
        $returnObject = false;

        // email is already used by an other nl_user with other ez_user_id
        if( $emailNlUserExists &&

            $emailNlUserEmail != $idNlUserEmail &&
            $emailNlUserEzUserId != $idNlUserEzUserId )
        {
            $returnStatus = -20;
        }
        // email is not valid because it is empty
        elseif ( !$idNlUserExists && !$emailNlUserExists &&
                 $emailNlUserEmail == '')
        {
            $returnStatus = -21;
        }

        // create new nl user
        elseif ( !$idNlUserExists && !$emailNlUserExists &&
                 $emailNlUserEmail != '')
        {
            $returnStatus = 40;
        }
        // update user  email1 = email2 id1 = id2
        elseif ( $idNlUserExists && $emailNlUserExists &&
                 $idNlUserId == $emailNlUserId )
        {
            $returnStatus = 41;
        }
        // update user => set new email
        elseif ( $idNlUserExists && !$emailNlUserExists &&
                 $emailNlUserEmail != '')
        {
            $returnStatus = 42;
            if( $updateNewEmail === true )
            {
                // check if email has is not set by an other user
                // this could happend when the nl user is imported
                // check this in where the user can change the email address
                // for example in datatype validation
                $idNlUser->setAttribute( 'email', $emailNlUserEmail );
                $idNlUser->store();
            }
        }
/*
         echo "ezUserId = $ezUserId <br>
              idNlUserEmail = $idNlUserEmail <br>
              idNlUserId = $idNlUserId <br>
              idNlUserEzUserId = $idNlUserEzUserId <br>
              idNlUserExists = $idNlUserExists <br><hr>

              email = $email<br>
              emailNlUserEmail = $emailNlUserEmail<br>
              emailNlUserId = $emailNlUserId <br>
              emailNlUserEzUserId = $emailNlUserEzUserId<br>
              emailNlUserExists = $emailNlUserExists<br>
              <hr> return stratus = $returnStatus <hr>";
              */
        //
        return $returnStatus;
    }
}

?>
