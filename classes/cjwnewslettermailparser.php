<?php
/**
 * File containing the CjwNewsletterMailParser class
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @see http://tools.ietf.org/html/rfc822#page-26
 * @filesource
 */
/**
 * parse mail object
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterMailParser
{
    /**
     *
     * @var ezcMailObject
     */
    private $EzcMailObject = null;

    /**
     *
     * @var ezcMailObject
     */
    private $MailboxItem = null;


    /**
     *
     * @var ezcMailParser
     */
    private $MailParser = null;

    /**
     * constructor
     *
     * @param CjwNewsletterMailboxItem $rawMailContent
     * @return void
     */
    function __construct( $mailboxItem )
    {
        $this->MailboxItem = $mailboxItem;
        $this->MailParser  = new ezcMailParser();

        // tmp dir for ezcmailparser have to be writeable
        // ... openbasedir restriction ...
        // projectvar/ cjw_newsletter/ tmp
        $this->MailParser->setTmpDir( $this->getTmpDir( true ) );

    }

    /**
     * tmp dir for mail parser
     *    ezvardir / cjw_newsletter/tmp/
     * @return string dirname
     */
    public function getTmpDir( $createDirIfNotExists = true )
    {

        $varDir = eZSys::varDirectory();

        // $dir = $varDir . "/cjw_newsletter/tmp/";
        $dir = eZDir::path( array( $varDir,
                                   'cjw_newsletter',
                                   'tmp'
                                  )
                          );

        $fileSep  = eZSys::fileSeparator();
        $filePath = $dir .$fileSep;

        if( $createDirIfNotExists === true )
        {
            if( !file_exists( $filePath ) )
            {
                eZDir::mkdir( $filePath, false, true );
            }
        }

        return $filePath;
    }

    /**
     * Basics parse
     *
     * @return array / boolean
     */
    public function parse( )
    {
        // parse set to mailobject
        $set = new ezcMailVariableSet( $this->MailboxItem->getRawMailMessageContent() );

        try
        {
            $ezcMailObjectArray = $this->MailParser->parseMail( $set );
        }
        catch( Exception $e )
        {
            CjwNewsletterLog::writeError(
                                            'CjwNewsletterMailparser::parse',
                                            'parseMail',
                                            'ezcMailParser->parseMail-failed',
                                             array( 'error-code' => $e->getMessage() ) );
            return false;
        }


        if ( count( $ezcMailObjectArray ) > 0 )
        {
            $this->EzcMailObject = $ezcMailObjectArray[0];

            // return standard email headers
            $parsedMailInfosArray = $this->getHeaders();

            // return x-cwl- email headers
            $parsedCjwMailHeaderArray = $this->getCjwHeaders();

            // merge header arrays
            $parseArray = array_merge( $parsedMailInfosArray, $parsedCjwMailHeaderArray );
            return $parseArray;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns standard headers
     *
     * @todo set some data to DB
     * @return array / boolean
     */
    private function getHeaders()
    {
        $ezcMailObject = $this->EzcMailObject;

        if( is_object(  $ezcMailObject ) )
        {
            $subject   = $ezcMailObject->getHeader( 'Subject' );
            $from      = $ezcMailObject->getHeader( 'From' );
            $to        = $ezcMailObject->getHeader( 'To' );
            $date      = $ezcMailObject->getHeader( 'Date' );
            $errorInfo = $this->fetchErrorByStatus( $ezcMailObject );

            return array( 'subject'         => $subject,
                          'from'            => $from,
                          'to'              => $to,
                          'error_code'      => $errorInfo[ 'error_code' ],
                          'email_send_date' => $date,
                          'final_recipient' => $errorInfo[ 'final_recipient' ] );
        }
        else
        {
            return false;
        }
    }

    /**
     * error in text or header?
     *
     * @todo try catch only experimentel to fetch normal mails, must be change with alternate
     * @param object $mailObject
     * @return string
     */
    private function fetchErrorByStatus( $mailObject )
    {
        // fetch parts of mailobject
        $arrayPartsOfezcMailObject = $mailObject->fetchParts();

        if ( isset( $arrayPartsOfezcMailObject[ 1 ] ) && is_object( $arrayPartsOfezcMailObject[ 1 ] ) && $arrayPartsOfezcMailObject[ 1 ] instanceof ezcMailDeliveryStatus )
        {
            $second = $arrayPartsOfezcMailObject[ 1 ]->__get( 'recipients' );

            if( isset( $second[ 0 ] ) && is_object( $second[ 0 ] ) )
            {
                $recipient = $second[ 0 ]->offsetGet( 'Final-Recipient' );
                //$action = $second[0]->offsetGet('Action');
                //$status = $second[0]->offsetGet('Status');
                $diagnostic = $second[ 0 ]->offsetGet( 'Diagnostic-Code' );
            }
            else
            {
                return 0;
            }

            // error code in recipients
            if ( !empty( $diagnostic ) )
            {
                $errorCode = $this->standardParser( $diagnostic );
            }
            // no error code in recipients => alternate error source in mail ( body text field )
            else
            {
                // get parts of ezcMailMultipartReport object, e.g. 'text'
                $ezcMailMultipartReportParts = $mailObject->body->getParts();

                // check, we search an ezcMailText object, which contains the 'text' field
                if ( is_object( $ezcMailMultipartReportParts[ 0 ] ) && $ezcMailMultipartReportParts[ 0 ] instanceof ezcMailText )
                {
                    // parse error code from textstring
                    $errorCode = $this->standardParser( $ezcMailMultipartReportParts[0]->__get( 'text' ) );
                }
            }

            return array( 'error_code' => $errorCode, 'final_recipient' => $recipient );
        }
        elseif ( isset( $arrayPartsOfezcMailObject[ 0 ] ) && is_object( $arrayPartsOfezcMailObject[ 0 ] ) && $arrayPartsOfezcMailObject[ 0 ] instanceof ezcMailText )
        {
            $text = $arrayPartsOfezcMailObject[ 0 ]->__get( 'text' );

            $errorCode = $this->standardParser( $text );

            return array( 'error_code' => $errorCode, 'final_recipient' => '' );
        }
        else
        {
            return 0;
        }

    }

    /**
     * returns error facts
     *
     * @param mixed $errorCode
     * @return string
     */
    private function standardParser( $errorCode )
    {
        $newArray = array();

        if ( is_array( $errorCode ) )
        {
            foreach ( $errorCode as $key => $line )
            {
                if ( preg_match( '( 5[0-9][0-9] )', $line, $resultForArray ) )
                {
                    array_push( $newArray, $errorCode[ $key ] );
                    $errorStringForArray = $newArray[ 0 ];
                    $errorNumForArray    = $resultForArray[ 0 ];
                    $bounceTyp           = 'Hardbounce';

                    //echo $bounceTyp."\n"."ErrorCode: ". $errorNumForArray ."\n"."ErrorDescription: ". $errorStringForArray."\n";
                    return $errorNumForArray;
                }
                elseif ( preg_match( '( 4[0-9][0-9] )', $line, $resultForArray ) )
                {
                    array_push( $newArray, $errorCode[$key] );
                    $errorStringForArray = $newArray[ 0 ];
                    $errorNumForArray    = $resultForArray[ 0 ];
                    $bounceTyp           = 'Softbounce';

                    //echo $bounceTyp."\n"."ErrorCode: ". $errorNumForArray ."\n"."ErrorDescription: ". $errorStringForArray."\n";
                    return $errorNumForArray;
                }


            }
        }
        // parse mail text
        elseif ( is_string( $errorCode ) )
        {

            // 550 5.7.0 Blocked
            // 511 5.1.1 unknown address or alias
            if ( preg_match( '(5[0-9][0-9] [0-9].[0-9].[0-9])', $errorCode, $resultForString ) )
            {
                $errorStringForString = $errorCode;
                $errorNumForString    = trim ( $resultForString[ 0 ] );
                return $errorNumForString;
            }
            else
            // (#4.4.1) / (#4.4.3) (#5.1.2)
            if ( preg_match( '(\\(#[4-5].[0-9].[0-9]\\))', $errorCode, $resultForString ) )
            {
                $errorStringForString = $errorCode;
                $errorNumForString    = trim ( $resultForString[ 0 ] );
                return $errorNumForString;
            }
            // _550_
            //  520 Unknown_recipient_or_domain
            elseif ( preg_match( '( 5[0-9][0-9] )', $errorCode, $resultForString ) )
            {
                $errorStringForString = $errorCode;
                $errorNumForString    = trim ( $resultForString[ 0 ] );
                $bounceTyp            = 'Hardbounce';

                //echo $bounceTyp."\n"."ErrorCode: ". $errorNumForString ."\n"."ErrorDescription: ". $errorStringForString."\n";
                return $errorNumForString;
            }
            // _450_
            elseif ( preg_match( '( 4[0-9][0-9] )', $errorCode, $resultForString ) )
            {
                $errorStringForString = $errorCode;
                $errorNumForString    = $resultForString[ 0 ];
                $bounceTyp            = 'Softbounce';

                //echo $bounceTyp."\n"."ErrorCode: ". $errorNumForString ."\n"."ErrorDescription: ". $errorStringForString."\n";
                return $errorNumForString;
            }

        }

        return '0';
    }

    /**
     * parse own xcjw headers
     *
     * @return unknown_type
     */
    private function getCjwHeaders()
    {
        $textArray = $this->MailboxItem->getRawMailMessageContent( true );
        $xcjwHeaderArray = array();
        $i = 0;

        // loop if smaller than 200 lines to have better performance
        // we expect that all x-cjw- headers are in the first 100-200 lines
        while( count( $textArray ) > $i && $i < 200 )
        {
            if ( strpos( $textArray[$i], 'x-cjwnl-' ) === 0 )
            {
                $explodeTextArray = explode( ':', $textArray[ $i ] );
                $key = trim( $explodeTextArray[ 0 ] );
                $value = trim( $explodeTextArray[ 1 ] );
                $xcjwHeaderArray[ $key ] = $value;
            }
            $i++;
        }
        return $xcjwHeaderArray;
    }
}

?>
