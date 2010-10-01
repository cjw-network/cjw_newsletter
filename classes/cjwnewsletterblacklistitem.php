<?php
/**
 * File containing the CjwNewsletterBlacklistItem class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Handle Blacklist of NL users or emails
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterBlacklistItem extends eZPersistentObject
{

    /**
     * constructor
     *
     * @param array $row
     * @return void
     */
    function CjwNewsletterBlacklistItem( $row )
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
                                         'email_hash' => array( 'name'  => 'EmailHash',
                                                        'datatype' => 'string',
                                                        'default'  => '',
                                                        'required' => true ),
                                         'email' => array( 'name'  => 'Email',
                                                        'datatype' => 'string',
                                                        'default'  => '',
                                                        'required' => true ),
                                         'newsletter_user_id' => array( 'name' => 'NewsletterUserId',
                                                        'datatype' => 'integer',
                                                        'default'  => 0,
                                                        'required' => false ),
                                         'created' => array( 'name'     => 'Created',
                                                             'datatype' => 'integer',
                                                             'default'  => 0,
                                                             'required' => true ),
                                         'creator_contentobject_id' => array( 'name'   => 'CreatorContentObjectId',
                                                        'datatype' => 'integer',
                                                        'default'  => 0,
                                                        'required' => true ),
                                         'note' => array( 'name'   => 'Note',
                                                        'datatype' => 'string',
                                                        'default'  => '',
                                                        'required' => false ),
                                        ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'function_attributes' => array( 'newsletter_user_object' => 'getNewsletterUserObject',
                                                      'creator' => 'getCreatorUserObject',
                                                      ),
                      'class_name' => 'CjwNewsletterBlacklistItem',
                      'name' => 'cjwnl_blacklist_item' );
    }

    /**
     * fetch CjwNewsletterBlacklistItem object by id
     * return false if not found
     *
     * @param integer $id
     * @param boolean $asObject
     * @return CjwNewsletterBlacklistItem
     */
    public static function create( $email, $note )
    {
        $newsletterUserObject = CjwNewsletterUser::fetchByEmail( $email );
        $newsletterUserId = 0;
        if( is_object( $newsletterUserObject ) )
        {
            $newsletterUserId = $newsletterUserObject->attribute('id');
        }
        $row = array( 'email'   => strtolower( $email ),
                      'note'    => $note,
                      'created' => time(),
                      'creator_contentobject_id' => eZUser::currentUserID(),
                      'email_hash' => self::generateEmailHash( $email ),
                      'newsletter_user_id' => $newsletterUserId
        );
        $object = new CjwNewsletterBlacklistItem( $row );
        return $object;
    }

    /**
     * generate emailHash for mail
     * @param string $email
     * @return string emailHash
     */
    public static function generateEmailHash( $email )
    {
        $emailHash = md5( strtolower( trim( $email ) ) );
        return $emailHash;
    }

    /**
     * fetch CjwNewsletterBlacklistItem object by id
     * return false if not found
     *
     * @param integer $id
     * @param boolean $asObject
     * @return CjwNewsletterBlacklistItem
     */
    public static function fetch( $id, $asObject = true )
    {
         return eZPersistentObject::fetchObject(
                                                    self::definition(),
                                                    null,
                                                    array( 'id' => (int) $id ),
                                                    $asObject
                                                );
    }

    /**
     * fetch CjwNewsletterBlacklistItem object by email
     * generae hash from email and look for existing hash
     * => so it is possible to delete the email make the user anonym
     * but we can ask the system if the email is on blacklist
     * return false if not found
     *
     * @param string $email
     * @param boolean $asObject
     * @return CjwNewsletterBlacklistItem
     */
    public static function fetchByEmail( $email, $asObject = true )
    {
        $condArray = array( 'email_hash' => self::generateEmailHash( $email ) );
        $objectList = eZPersistentObject::fetchObjectList(
                                                    self::definition(),
                                                    null,
                                                    $condArray,
                                                    null,
                                                    null,
                                                    $asObject,
                                                    null,
                                                    null,
                                                    null,
                                                    null );

        if( count( $objectList ) >= 1 )
        {
            return $objectList[0];
        }
        else
        {
            return false;
        }
    }

    /**
     * Check if given email is on blacklist
     *
     * @param string $email
     * @return boolean
     */
    static function isEmailOnBlacklist( $email )
    {
        $object = CjwNewsletterBlacklistItem::fetchByEmail( $email );
        if( is_object( $object ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * fetch all blacklist items
     *
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return unknown_type
     */
    static public function fetchAllBlacklistItems( $limit = 50, $offset = 0, $sortByArray = null, $asObject = true )
    {
        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        if( !is_array( $sortByArray ))
        {
            $sortByArray = array( 'id' => false );
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
     * count all blacklsit items
     *
     * @return integer
     */
    static public function fetchAllBlacklistItemsCount( )
    {
        $count = eZPersistentObject::count(
                             self::definition(),
                             array( ),
                             'id' );
        return $count;
    }

    /**
     *
     * @return CjwNewsletterUser
     */
    function getNewsletterUserObject()
    {
        if ( $this->attribute( 'newsletter_user_id' ) != 0 )
        {
            $user = CjwNewsletterUser::fetch( $this->attribute( 'newsletter_user_id' ) );
            return $user;
        }
        else
        {
            return false;
        }
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
     * if nl user exists update this data to
     * (non-PHPdoc)
     * @see kernel/classes/eZPersistentObject#store($fieldFilters)
    */
    public function store( $fieldFilters = null )
    {
        $newsletterUserObject = $this->getNewsletterUserObject();
        if( is_object( $newsletterUserObject ) )
        {
            $newsletterUserObject->setBlacklisted();
        }
        parent::store( $fieldFilters );
    }

}

?>