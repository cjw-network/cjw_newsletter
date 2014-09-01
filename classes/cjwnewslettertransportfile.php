<?php
/**
 * File containing the CjwNewsletterTransportFile class
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Storing a mail to a file
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterTransportFile implements ezcMailTransport
{
    /**
     *
     * @var string
     */
    public $mailDir = 'var/log/mail';

    /**
     * Constructs a new CjwNewsletterTransportFile
     *
     * @param string $mailDir
     * @return void
     */
    public function __construct( $mailDir = 'var/log/mail' )
    {
        // $this->mailDir = eZSys::siteDir().eZSys::varDirectory().'/log/mail';
        if ( is_dir( $mailDir ) or eZDir::mkdir( $mailDir, false, true ) )
        {
            $this->mailDir = $mailDir;
        }
        else
        {
            // TODO Fehlerbehandlung wenn verzeichnis nicht angelegt werden kann
        }
    }

    /**
     * Stores the mail $mail to the filesystem
     *
     * @throws ezcMailTransportException
     *         if the mail was not accepted for delivery by the MTA.
     * @param ezcMail $mail
     * @return void
     */
    public function send( ezcMail $mail )
    {
        $mail->appendExcludeHeaders( array( 'to', 'subject' ) );
        $headers = rtrim( $mail->generateHeaders() ); // rtrim removes the linebreak at the end, mail doesn't want it.

        if ( ( count( $mail->to ) + count( $mail->cc ) + count( $mail->bcc ) ) < 1 )
        {
            throw new ezcMailTransportException( 'No recipient addresses found in message header.' );
        }
        $emailReturnPath = '';
        if ( isset( $mail->returnPath ) )
        {
            $emailReturnPath = $mail->returnPath->email;
        }


        $success = $this->createMailFile( ezcMailTools::composeEmailAddresses( $mail->to ),
                         $mail->getHeader( 'Subject' ),
                         $mail->generateBody(),
                         $headers,
                         $emailReturnPath );
        if ( $success === false )
        {
            throw new ezcMailTransportException( 'The email could not be sent by sendmail' );
        }
    }

    /**
     *
     * @param unknown_type $receiver
     * @param unknown_type $subject
     * @param unknown_type $message
     * @param unknown_type $extraHeaders
     * @param unknown_type $emailReturnPath
     * @return file
     */
    function createMailFile( $receiver, $subject, $message, $extraHeaders, $emailReturnPath = '' )
    {
        $sys = eZSys::instance();
        $lineBreak =  ($sys->osType() == 'win32' ? "\r\n" : "\n" );
        // $separator =  ($sys->osType() == 'win32' ? "\\" : "/" );
        // $fileName = date("Ymd") .'-' .date("His").'-'.rand().'.mail';
        $fileName = time().'-'.rand().'-cjw_nl.mail';
        // $mailDir = eZSys::siteDir().eZSys::varDirectory().'/log/mail';
        // $mailDir = eZSys::siteDir().'var/log/mail';

        $data = $extraHeaders.$lineBreak;
        if ( $emailReturnPath != '')
            $data .= "Return-Path: <".$emailReturnPath.">".$lineBreak;

        $data .= "To: ".$receiver.$lineBreak;
        $data .= "Subject: ".$subject.$lineBreak;
        // $data .= "From: ".$emailSender.$lineBreak;
        $data .=  $lineBreak;
        $data .= $message;

        $data = preg_replace('/(\r\n|\r|\n)/', "\r\n", $data);

        return eZFile::create( $fileName, $this->mailDir, $data );
    }
}
?>