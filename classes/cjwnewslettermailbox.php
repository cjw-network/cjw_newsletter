<?php
/**
 * File containing the CjwNewsletterMailbox class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Connect with mailaccount and fetch/parse mailitems
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterMailbox extends eZPersistentObject
{

    /**
     * Store ezc...Transport object global
     */
    var $TransportObject = null;

    /**
     * constructor
     *
     * @param mixed $row
     * @return void
     */
    function __construct( $row = array() )
    {
        parent::__construct( $row );
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
                                         'email' => array( 'name'  => 'Email',
                                                        'datatype' => 'string',
                                                        'default'  => '',
                                                        'required' => true ),
                                         'server' => array( 'name' => 'Server',
                                                        'datatype' => 'string',
                                                        'default'  => '',
                                                        'required' => true ),
                                         'port' => array( 'name'   => 'Port',
                                                        'datatype' => 'integer',
                                                        'default'  => 0,
                                                        'required' => true ),
                                         'user' => array( 'name'   => 'User',
                                                        'datatype' => 'string',
                                                        'default'  => '',
                                                        'required' => true ),
                                         'password' => array( 'name' => 'Password',
                                                        'datatype'   => 'string',
                                                        'default'    => '',
                                                        'required'   => true ),
                                         'type' => array( 'name'     => 'Type',
                                                        'datatype' => 'string',
                                                        'default'  => '',
                                                        'required' => true ),
                                         'is_activated' => array( 'name'     => 'IsActivated',
                                                                  'datatype' => 'integer',
                                                                  'default'  => 0,
                                                                  'required' => true ),
                                         'is_ssl' => array( 'name'     => 'IsSsl',
                                                            'datatype' => 'integer',
                                                            'default'  => 0,
                                                            'required' => true ),
                                         'delete_mails_from_server' => array( 'name'     => 'DeleteMailsFromServer',
                                                                              'datatype' => 'integer',
                                                                              'default'  => 0,
                                                                              'required' => true ),
                                         'last_server_connect' => array( 'name'     => 'LastServerConnect',
                                                                         'datatype' => 'integer',
                                                                         'default'  => 0,
                                                                         'required' => true )),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'CjwNewsletterMailbox',
                      'name' => 'cjwnl_mailbox' );
    }

    /**
     * fetch all active mailboxes
     *
     * @return object
     */
    public static function fetchAllActiveMailboxes()
    {
        $objectList = eZPersistentObject::fetchObjectList(
                                                            self::definition(),
                                                            null,
                                                            array( 'is_activated' => 1 ),
                                                            null,
                                                            null,
                                                            true
                                                         );


        return $objectList;
    }

    /**
     * fetch all exists mailboxes
     *
     * @return array
     */
    public static function fetchAllMailboxes()
    {
        $objectList = eZPersistentObject::fetchObjectList(
                                                            self::definition(),
                                                            null,
                                                            null,
                                                            null,
                                                            null,
                                                            true
                                                         );

        return $objectList;
    }

    /**
     * fetch data of mailbox to edit they in frontend
     *
     * @param integer $id
     * @param boolean $asObject
     * @return object
     */
    static public function fetchMailboxDataForEdit( $id, $asObject = true )
    {
        $mailboxObject = eZPersistentObject::fetchObject(
                                                            self::definition(),
                                                            null,
                                                            array( 'id' => $id ),
                                                            $asObject
                                                         );
        return $mailboxObject;
    }


    /**
     * connect to all mailboxes which are activated and store all mails in our db
     *
     * @return array
     */
    public static function collectMailsFromActiveMailboxes()
    {
        $mailboxesProcessArray = array();

        // fetch all acitive mailboxes
        $mailboxes = self::fetchAllActiveMailboxes();

        if ( is_array( $mailboxes ) )
        {
            // foreach mailbox connect and get mails
            foreach ( $mailboxes as $mailbox )
            {
                if ( is_object( $mailbox ) )
                {
                    try
                    {
                        // connect to mailbox
                        $connectResult = $mailbox->connect();

                        if( is_object( $connectResult ) )
                        {
                            CjwNewsletterLog::writeDebug(
                                                    'CjwNewsletterMailbox::collectMailsFromActiveMailboxes',
                                                    'mailbox',
                                                    'connect-ok',
                                                     array( 'mailbox_id' => $mailbox->attribute('id') ) );

                            try
                            {
                                $mailboxesProcessArray[ $mailbox->attribute( 'id' ) ] = $mailbox->fetchMails();
                                $mailbox->disconnect();
                            }
                            catch( Exception $e )
                            {
                                CjwNewsletterLog::writeError(
                                            'CjwNewsletterMailparser::parse',
                                            'parseMail',
                                            'ezcMailParser->parseMail-failed',
                                             array( 'error-code' => $e->getMessage() ) );
                                return $e->getMessage();
                            }

                        }
                        else
                        {
                            $mailboxesProcessArray[ $mailbox->attribute( 'id' ) ] = 'connection failed'. $connectResult;
                            CjwNewsletterLog::writeError(
                                                    'CjwNewsletterMailbox::collectMailsFromActiveMailboxes',
                                                    'mailbox',
                                                    'connect-failed',
                                                     array( 'error_code' => $connectResult,
                                                            'mailbox_id' => $mailbox->attribute('id') )
                                                     );
                        }

                    }
                    catch( Exception $e )
                    {
                        CjwNewsletterLog::writeError(
                                                        'CjwNewsletterMailbox::collectMailsFromActiveMailboxes',
                                                        'mailbox',
                                                        'connect-failed-other',
                                                         array( 'error-code' => $e->getMessage() ) );
                        return $e->getMessage();
                    }

                }
            }

            return $mailboxesProcessArray;
        }
        else
        {
            return false;
        }
    }


    /**
     * parse all mailitems of all active mailboxes and get status code ...
     *
     * @return array
     */
    public static function parseActiveMailboxItems()
    {
        // fetch mails which has a empty progressed_field ( unparsed mails )
        $objectList = eZPersistentObject::fetchObjectList(
                                                            CjwNewsletterMailboxItem::definition(),
                                                            null,
                                                            array( 'processed' => 0 ),
                                                            null,
                                                            null,
                                                            $asObject = true,
                                                            null,
                                                            null
                                                          );

       $parseResultArray = array();

       foreach ( $objectList as $mailboxItem )
       {
            $parseResultArray[ $mailboxItem->attribute( 'id' ) ] = $mailboxItem->parseMail();
       }

       return $parseResultArray;
    }


    /**
     * store changed or new mailbox in database
     *
     * @param array $mailboxDataArray
     * @return boolean
     */
    public function storeMailboxData( $id, $mailboxDataArray )
    {
        // edit
        if ( $id != 0 )
        {
            $mailboxObject = $this->fetchMailboxDataForEdit( $id );
        }
        // add
        else
        {
            $mailboxObject = $this;
        }

        // set data
        if ( is_object( $mailboxObject ) )
        {
            foreach ( $mailboxDataArray as $key => $value )
            {
                $mailboxObject->setAttribute( $key, $value );
            }
        }
        // save
        $mailboxObject->store();

        return true;
    }

    /**
     * connect with mailaccount
     *
     * @return object / boolean
     */
    public function connect()
    {
        // current mailbox id
        $mailboxId = $this->attribute( 'id' );

        // current mailbox type, for pop3-/imap switch
        $mailboxType = $this->attribute( 'type' );

        // login data
        $server                = $this->attribute( 'server' );
        $user                  = $this->attribute( 'user' );
        $password              = $this->attribute( 'password' );
        $port                  = $this->attribute( 'port' );
        $ssl                   = (boolean) $this->attribute( 'is_ssl' );
        $deleteMailsFromServer = (boolean) $this->attribute( 'delete_mails_from_server' );

        if( $port > 0 )
        {
            $serverPort = $port;
        }
        else
        {
            // let choose ezc the right port if not set
            $serverPort = null;
        }

        $ezcTransportObject = null;

        $settingArray = array( 'mailbox_id'               => $mailboxId,
                               'type'                     => $mailboxType,
                               'server'                   => $server,
                               'user'                     => $user,
                               'password'                 => $password,
                               'port'                     => $port,
                               'is_ssl'                   => $ssl,
                               'delete_mails_from_server' => $deleteMailsFromServer );
        try
        {
            // create transport object
            switch ( $mailboxType )
            {
                case 'imap':
                    $options = new ezcMailImapTransportOptions();
                    $options->ssl = $ssl;
                    $options->timeout = 3;
                    $ezcTransportObject = new ezcMailImapTransport( $server, $serverPort, $options );
                    break;
                case 'pop3':
                    $options = new ezcMailPop3TransportOptions();
                    $options->ssl = $ssl;
                    $options->timeout = 3;
                    // $options->authenticationMethod = ezcMailPop3Transport::AUTH_APOP;
                    $ezcTransportObject = new ezcMailPop3Transport( $server, $serverPort, $options );
                    break;
                default:
                    CjwNewsletterLog::writeError(
                                                'CjwNewsletterMailbox::connect',
                                                'mailbox',
                                                'connect-failed',
                                                 array( 'error-code' => $e->getMessage() ) );
                    return $e->getMessage();

            }
        }
        catch( Exception $e )
        {
            CjwNewsletterLog::writeError(
                                            'authenticate ezcMailTransport CjwNewsletterMailbox::connect',
                                            'mailbox',
                                            'connect-failed',
                                             array( 'error-code' => $e->getMessage() ) );
            return $e->getMessage();
        }

        try
        {
            // authenticate twise is not allowed
            $ezcTransportObject->authenticate( $user, $password );
        }
        catch( Exception $e )
        {
            CjwNewsletterLog::writeError(
                                            'CjwNewsletterMailbox::connect',
                                            'mailbox',
                                            'authenticate-failed',
                                             array_merge( array( 'error-code' => $e->getMessage() ),
                                                          $settingArray )  );
            return $e->getMessage();
        }

        try
        {

            switch ( $mailboxType )
            {
                case 'imap':
                    $ezcTransportObject->selectMailbox( 'Inbox' );
                    break;
            }

            $this->TransportObject = $ezcTransportObject;

            // set connect time
            $this->setAttribute( 'last_server_connect', time() );
            $this->store();

            CjwNewsletterLog::writeDebug(
                                        'CjwNewsletterMailbox::connect',
                                        'mailbox',
                                        'connect-ok',
                                         $settingArray );

            return $ezcTransportObject;

        }
        catch( Exception $e )
        {
            CjwNewsletterLog::writeError(
                                            'CjwNewsletterMailbox::connect',
                                            'mailbox',
                                            'selectBox-failed',
                                             array( 'error-code' => $e->getMessage() ) );

            return $e->getMessage();
        }

    }

    /**
     * disconnect connection
     * @return unknown_type
     */
    public function disconnect()
    {
        if( is_object( $this->TransportObject ) )
        {
            $this->TransportObject->disconnect();
        }
    }


    /**
     * fetch mails to parse and/or store
     *
     * @return void
     */
    public function fetchMails( )
    {
        $statusArray = array( 'added'   => array(),
                              'exists' => array(),
                              'failed'  => array() );
        $mailboxId = $this->attribute( 'id' );
        $mailboxDeleteMailsFromServer = (boolean) $this->attribute( 'delete_mails_from_server' );

        if ( is_object( $this->TransportObject ) )
        {
            $transport = $this->TransportObject;

            try
            {
                // it is possible that not all pop3 server understand this
                // array( message_num => unique_id );
                // array( 1 => '000001fc4420e93a', 2 => '000001fd4420e93a' );
                $uniqueIdentifierArray = $transport->listUniqueIdentifiers();
            }
            catch( Exception $e )
            {
                $uniqueIdentifiers = false;
                CjwNewsletterLog::writeError(
                                            'CjwNewsletterMailbox::fetchMails',
                                            'mailbox',
                                            'listUniqueIdentifiers-failed',
                                             array( 'error-code' => $e->getMessage() ) );
            }

            try
            {
                // array( message_id => message_size );
                // array( 2 => 1700, 5 => 1450 );
                $messageIdArray = $transport->listMessages( );
            }
            catch( Exception $e )
            {
                $messageIdNumbers = false;
                CjwNewsletterLog::writeError(
                                            'CjwNewsletterMailbox::fetchMails',
                                            'mailbox',
                                            'listMessages-failed',
                                             array( 'error-code' => $e->getMessage() ) );
            }

            // array( message_id => message_identifier )
            $messageNumberArray = array();

            // only fetch messages from server which are not in the db
            // use message_identifier for check
            $existingMessageIdentifierArray = $this->extractAllExistingIdentifiers( $uniqueIdentifierArray );

            foreach ( $messageIdArray as $messageId => $messageSize )
            {
                if( isset( $uniqueIdentifierArray[ $messageId ] ) )
                {
                    $uniqueIdentifier = $uniqueIdentifierArray[ $messageId ];
                }
                else
                {
                    $uniqueIdentifier = false;
                }
                if( array_key_exists( $uniqueIdentifier, $existingMessageIdentifierArray ) )
                {
                    $statusArray['exists'][ $messageId ] = $uniqueIdentifier;
                }
                else
                {
                    $messageNumberArray[ $messageId ] = $uniqueIdentifier;
                }
            }

            if ( count( $messageNumberArray ) > 0 )
            {
                // only fetch x item at once to avoid script timeout ... if call from admin frontend
                // the cronjob may be has other settings
                $fetchLimit = 50;
                $counter = 0;

                foreach ( $messageNumberArray as $messageId => $messageIdentifier )
                {
                    if( $counter >= $fetchLimit )
                    {
                        break;
                    }
                    else
                    {
                        // create mailobject from message id
                        // $mailboxDeleteMailsFromServer == true, set delete flag for current message
                        $mailObject = $transport->fetchByMessageNr( $messageId, $mailboxDeleteMailsFromServer );

                        // convert mailobject to string with own function
                        $messageString = $this->convertMailToString( $mailObject );

                        if( $messageIdentifier === false )
                        {
                            $messageIdentifier = 'cjwnl_'.md5( $messageString );
                        }

                        // if messageString has content
                        if ( $messageString != null )
                        {
                            // add item to DB / Filesystem
                            $addResult = CjwNewsletterMailboxItem::addMailboxItem( $mailboxId, $messageIdentifier, $messageId, $messageString );
                            if ( is_object( $addResult ) )
                            {
                                $statusArray[ 'added' ] [ $messageId ] =  $messageIdentifier;
                            }
                            else
                            {
                                $statusArray[ 'exists' ] [ $messageId ] = $messageIdentifier;
                            }
                            unset( $addResult );
                        }
                        else
                        {
                            $statusArray[ 'failed' ] [ $messageId ] = $messageIdentifier;
                        }
                        unset( $messageString );
                        unset( $mailObject );
                    }
                    $counter++;
                }

                // delete messages with delete flag from mailbox
                switch ( $this->attribute('type') )
                {
                    case 'imap':
                        $transport->expunge();
                        break;
                }
            }
            else
            {
                return $statusArray;
            }
        }
        return $statusArray;
    }

    /**
     * check if  message_idendifier are in db if not return it otherwise ignore
     * it is used to avoid connections
     * array( message_id => message_identifer )
     * @param $array
     * @return unknown_type
     */
    private function extractAllExistingIdentifiers( $messageIdentifierArray )
    {
        $existingMessageIdentifierArray = array();
        $mailboxId = (int) $this->attribute('id');
        $identifierImplodeString = '';

        foreach( $messageIdentifierArray as $identifier )
        {
            $identifierImplodeString .= "'$identifier',";
        }

        $db = eZDB::instance();
        $sql = "SELECT id, message_identifier FROM cjwnl_mailbox_item
                WHERE mailbox_id=$mailboxId
                AND message_identifier IN ( $identifierImplodeString -1 )";
        $rows = $db->arrayQuery( $sql );
        foreach( $rows as $row )
        {
            $existingMessageIdentifierArray[ $row[ 'message_identifier' ] ] = 'item_'.$row['id'];
        }
        return $existingMessageIdentifierArray;
    }

    /**
     * set transport object
     *
     * @param object $object
     * @return boolean
     */
    public function setTransportObject( $object )
    {
        if ( is_object( $object ) )
        {
            $this->TransportObject = $object;
        }
        else
        {
            return false;
        }
    }

    /**
     * convert mailobject to string
     *
     * @param object $mailObjectSet
     * @return string / boolean
     */
    public function convertMailToString( $mailObjectSet )
    {
        if ( is_object( $mailObjectSet ) )
        {
            $rawMail = '';
            while ( ( $line = $mailObjectSet->getNextLine() ) !== null )
            {
                // if memory_usage is to high return false 10 MB files should be ok normal usage is 1672448
                // on some imap connections i had such a problem
                // after new fetch it was ok
                if( memory_get_usage( true ) > 3000000 )
                    return false;
                $rawMail .= $line;
            }
            return $rawMail;
        }
        else
        {
            return false;
        }
    }

}

?>