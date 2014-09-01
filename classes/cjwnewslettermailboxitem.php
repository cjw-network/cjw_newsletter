<?php
/**
 * File containing the CjwNewsletterMailboxItem class
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @see http://tools.ietf.org/html/rfc822#page-26
 * @filesource
 */
/**
 * Store mail items and parse
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterMailboxItem extends eZPersistentObject
{
    /**
     * store content string of mailobject
     *
     * @var string
     */
    var $MessageString = null;

    /**
     * constructor
     *
     * @param unknown_type $row
     * @return void
     */
    function CjwNewsletterMailboxItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /**
     * data fields ....
     *
     * @return void
     */
    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'mailbox_id' => array( 'name' => 'MailboxId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'message_id' => array( 'name' => 'MessageId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'message_identifier' => array( 'name' => 'MessageIdentifier',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => true ),
                                         'message_size' => array( 'name' => 'MessageSize',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'created' => array( 'name' => 'Created',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'processed' => array( 'name' => 'Processed',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'newsletter_user_id' => array( 'name' => 'NewsletterUserId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'edition_send_id' => array( 'name' => 'EditionSendId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'edition_send_item_id' => array( 'name' => 'EditionSendItemId',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'bounce_code' => array( 'name' => 'BounceCode',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => false ),
                                         'email_from' => array( 'name' => 'EmailFrom',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => false ),
                                         'email_to' => array( 'name' => 'EmailTo',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => false ),
                                         'email_subject' => array( 'name' => 'EmailSubject',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => false ),
                                         'email_send_date' => array( 'name' => 'EmailSendDate',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => false )),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'CjwNewsletterMailboxItem',
                      'name' => 'cjwnl_mailbox_item',
                      'function_attributes' => array( 'file_path' => 'getFilePath',
                                                      'is_bounce' => 'isBounce',
                                                      'is_system_bounce' => 'isSystemBounce'

                                                       ),
                      );
    }

    /**
     * fetch CjwNewsletterMailboxItem object by id
     * return false if not found
     *
     * @param integer $id
     * @return CjwNewsletterMailboxItem or false
     */
    public static function fetch( $id, $asObject = true )
    {
         return eZPersistentObject::fetchObject(
                                                    CjwNewsletterMailboxItem::definition(),
                                                    null,
                                                    array( 'id' => (int) $id ),
                                                    $asObject
                                                );
    }

    /**
     * fetch by message id
     *
     * @param integer $mailboxId
     * @param string $messageIdentifier
     * @param boolean $asObject
     * @return object or false
     */
    public static function fetchByMailboxIdMessageIdentifier( $mailboxId, $messageIdentifier, $asObject = true )
    {
         return eZPersistentObject::fetchObject(
                                                    self::definition(),
                                                    null,
                                                    array( 'mailbox_id' => (int) $mailboxId,
                                                           'message_identifier' =>  $messageIdentifier ),
                                                    $asObject
                                                );
    }

    /**
     * adding an mailobject fetched from imap or pop3
     * it will store it to db and local filesystem
     *
     * @todo parse created from mail header
     * @param integer $mailboxId
     * @param integer $messageId
     * @param string $messageString
     * @return object / false if not create
     */
    public static function addMailboxItem( $mailboxId, $messageIdentifier, $messageId, $messageString )
    {
        $foundMessage = self::fetchByMailboxIdMessageIdentifier( $mailboxId, $messageIdentifier );

        if ( !is_object( $foundMessage ) )
        {
            // object with fetch id not exists, than start the store progress
            $row = array( 'created'         => time(),
                          'mailbox_id'      => $mailboxId,
                          'message_id'      => $messageId,
                          'message_identifier' => $messageIdentifier,
                          'message_size' => strlen( $messageString )
             );

            $newMailboxItemObject = new CjwNewsletterMailboxItem( $row );
            $newMailboxItemObject->store();

            $newMailboxItemObject->MessageString = $messageString;

            // store message on filesystem
            $newMailboxItemObject->storeMessageToFilesystem();

            return $newMailboxItemObject;
        }
        else
        {
            return false;
        }
    }

    /**
     * fetch alls mailbox items
     *
     * @param integer $limit
     * @param integer $offset
     * @param boolean $asObject
     * @return unknown_type
     */
    static public function fetchAllMailboxItems( $limit = 50, $offset = 0, $sortByArray = null, $asObject = true )
    {
        if ( (int) $limit != 0 )
        {
            $limitArr = array( 'limit' => $limit, 'offset' => $offset );
        }

        if( !is_array( $sortByArray ))
        {
            $sortByArray = array( //'created' => true,
                                  'id' => true,
            //                      'message_id' => true
            );
        }
        $condArray = array( /*'bounce_code' => array( '>', 0 )*/ );

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
     * count all mail items in mailbox
     *
     * @return uinteger
     */
    static public function fetchAllMailboxItemsCount( )
    {
        $count = eZPersistentObject::count(
                             self::definition(),
                             array( ),
                             'id' );
        return $count;
    }

    /**
     * store mailitem on filesystem
     *
     * @todo eZFile::create result
     * @return void
     */
    private function storeMessageToFilesystem()
    {

        $mailboxItemId = $this->attribute( 'id' );
        $mailboxId     = $this->attribute( 'mailbox_id' );
        $messageId     = $this->attribute( 'message_id' );

        $currentTimestamp = time();

        // return filepath
        $filePathArray = $this->getFilePathArray();

        // return content string of mail item
        $messageData = $this->MessageString;

        // create file in path with content
        $createResult = eZFile::create( $filePathArray[ 'file_name' ], $filePathArray[ 'file_dir' ], $messageData );
    }

    /**
     * get file path
     *
     * @return string path to the current mailfile
     */
    public function getFilePath()
    {
        $filePathArray = $this->getFilePathArray();
        return $filePathArray[ 'file_path' ];
    }

    /**
     * create the dir and filename for the current mailboxItem
     *
     * @return array array( 'file_path' => $filePath,
     *                 'file_dir'  => $dir,
     *                 'file_name' => $fileName )
     */
    public function getFilePathArray( )
    {
        $mailboxItemId     = $this->attribute( 'id' );
        $mailboxId         = $this->attribute( 'mailbox_id' );
        $messageId         = $this->attribute( 'message_id' );
        $messageIdentifier = $this->attribute( 'message_identifier' );
        $createTimestamp   = $this->attribute( 'created' );

        $varDir = eZSys::varDirectory();
        $year  = date( 'Y', $createTimestamp );
        $month = date( 'm', $createTimestamp );
        $day   = date( 'd', $createTimestamp );

        // $dir = $varDir . "/cjw_newsletter/mailbox/$mailboxId/$year/$month/$day/";
        $dir = eZDir::path( array( $varDir,
                                   'cjw_newsletter',
                                   'mailbox',
                                    $mailboxId,
                                    $year,
                                    $month,
                                    $day
                                  )
                          );

        $fileName = "$mailboxId-$year$month$day-$mailboxItemId.mail";
        $fileSep = eZSys::fileSeparator();
        $filePath = $dir .$fileSep. $fileName;
        return array( 'file_path' => $filePath,
                      'file_dir'  => $dir,
                      'file_name' => $fileName );
    }



    /**
     * parse mail
     *
     * @return array
     */
    public function parseMail()
    {
        $mailParserObject = new CjwNewsletterMailParser( $this );
        $parseResult = $mailParserObject->parse();
        $this->saveParsedInfos( $parseResult );
        return $parseResult;
    }

    /**
     * try to read the raq mailmessage from local filesystem
     *
     * @return string mailmessage or false
     */
    public function getRawMailMessageContent( $asArray = false )
    {
        $filePath = $this->getFilePath();
        if ( file_exists( $filePath ) )
        {
            if ( $asArray === false )
            {
                return file_get_contents( $filePath );
            }
            else
            {
                return file( $filePath );
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * saved parsed infos from ezcMailObject into database
     *
     * @param $parsedResult
     */
    private function saveParsedInfos( $parsedResult )
    {
        $this->setAttribute( 'email_from', $parsedResult[ 'from' ] );
        $this->setAttribute( 'email_to', $parsedResult[ 'to' ] );
        $this->setAttribute( 'email_subject', $parsedResult['subject' ] );
        $this->setAttribute( 'bounce_code', $parsedResult[ 'error_code' ] );
        //$this->setAttribute( 'final_recipient', $parsedResult[ 'final_recipient' ] );
        $this->setAttribute( 'email_send_date', $this->convertEmailSendDateToTimestamp( $parsedResult[ 'email_send_date' ] ) );

        // if x-cjwnl-senditem hash was set in bounce mail than fetch some ez data
        if ( isset( $parsedResult[ 'x-cjwnl-senditem' ] ) )
        {
            $sendItemHash = $parsedResult[ 'x-cjwnl-senditem' ];

            // try to fetch edition send item object
            $sendItemObject = CjwNewsletterEditionSendItem::fetchByHash( $sendItemHash, true );

            if( is_object( $sendItemObject ) )
            {
                $newsletterUserId   = $sendItemObject->attribute( 'newsletter_user_id' );
                $editionSendId      = $sendItemObject->attribute( 'edition_send_id' );
                $editionSendItemId  = $sendItemObject->attribute( 'id' );

                $this->setAttribute( 'newsletter_user_id', $newsletterUserId );
                $this->setAttribute( 'edition_send_id', $editionSendId );
                $this->setAttribute( 'edition_send_item_id', $editionSendItemId );

                if ( $this->isBounce() )
                {
                    $sendItemObject->setBounced();
                    $newsletterUser = $sendItemObject->attribute( 'newsletter_user_object' );

                    if( is_object( $newsletterUser ) )
                    {
                        // bounce nl user
                        $isHardBounce = false;
                        $newsletterUser->setBounced( $isHardBounce );
                    }
                }
            }
        }
        // if only set 'x-cjwnl-user'
        elseif ( isset( $parsedResult[ 'x-cjwnl-user' ] ) )
        {
            $newsletterUser = CjwNewsletterUser::fetchByHash( $sendItemHash, true );

            if ( is_object( $sendItemObject ) )
            {
                $newsletterUserId = $newsletterUser->attribute('id');
                $this->setAttribute( 'newsletter_user_id', $newsletterUserId );

                if ( $this->isBounce() )
                {
                    // bounce nl user
                    $isHardBounce = false;
                    $newsletterUser->setBounced( $isHardBounce );
                }
            }
        }

        CjwNewsletterLog::writeDebug(
                                    'parse_result CjwNewsletterMailboxItem::saveParsedInfos',
                                    'mailbox_item',
                                     $this->attribute( 'id' ),
                                     $parsedResult
                                     );

        // item is parsed
        $this->setAttribute( 'processed', time() );
        $this->store();
    }

    /**
     * convert given string to timestamp
     *
     * format: Tue, 27 Oct 2009 15:27:35 +0100 => timestamp
     *
     * @param string $emailSendDate
     * @return timestamp
     */
    private function convertEmailSendDateToTimestamp( $emailSendDate )
    {
        return strtotime( $emailSendDate );
    }

    /**
     * if bounce_code is a bounce
     *
     * @return boolean
     */
    public function isBounce()
    {
        $bounceCode = $this->attribute( 'bounce_code' );
        if( $bounceCode === 0 ||
            $bounceCode === '' ||
            $bounceCode === '0' ||
            $bounceCode === null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * check if current item is a nl system bounce
     * isBounce() has to be true and a newsletterUserId has to be detected
     * @return boolean
     */
    public function isSystemBounce()
    {
        if( $this->isBounce() === true &&
            $this->attribute( 'newsletter_user_id' ) != 0 )
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