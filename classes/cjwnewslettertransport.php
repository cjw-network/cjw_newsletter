<?php
/**
 * File containing the CjwNewsletterTransport class
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @filesource
 */
/**
 * Generate output and mail
 *
 * @version //autogentag//
 * @package cjw_newsletter
 */
class CjwNewsletterTransport
{

    /**
    * Holds the properties of this class.
    *
    * @var array(string=>mixed)
    */
    protected $properties = array();

    /**
     * Set all default values
     *
     * @param string $transportMethod
     * @return void
     */
    function __construct( $transportMethod = 'file' )
    {
        $this->transportMethod = $transportMethod;
    }

    /**
     * Choose opportuneness send-logic
     *
     * @param object $ezcMailComposerObject
     * @return error message / boolean
     */
    public function send( ezcMail $ezcMailComposerObject )
    {
        $iniTransport = $this->transportMethod;
        $cjwNewsletterINI = eZINI::instance( 'cjw_newsletter.ini' );

        switch ( $iniTransport )
        {
            case 'smtp':
            {
                // read smtp settings from ini
                $smtpTransportServer = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportServer' );
                $smtpTransportPort = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportPort' );
                $smtpTransportUser = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportUser' );
                $smtpTransportPassword = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportPassword' );

                $options = new ezcMailSmtpTransportOptions();
                $transport = new ezcMailSmtpTransport(  $smtpTransportServer,
                                                        $smtpTransportUser,
                                                        $smtpTransportPassword,
                                                        $smtpTransportPort,
                                                        $options );

            } break;
            case 'file':
            {
                // var/log/mail
                $mailDir = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'FileTransportMailDir' );
                $transport = new CjwNewsletterTransportFile( $mailDir );
            } break;
            case 'sendmail':
            case 'mta':
            {
                $transport = new ezcMailMtaTransport();
            } break;
        }

        try
        {
            $transport->send( $ezcMailComposerObject );
            // CjwNewsletterLog::writeInfo( 'email send ok', 'CjwNewsletterTransport', 'send' );

            return true;
        }
        catch ( ezcMailTransportException $e )
        {
            // error by transport with tracking
            eZDebug::writeError( 'CjwNewsletterTransport:send: ' . $e->getMessage()  );
            return $e;
        }
    }

    /**
     * Sets the property $name to $value.
     *
     * @param string $name
     * @param string $value
     * @throws ezcBasePropertyNotFoundException
     *         if the property $name does not exist
     * @throws ezcBaseValueException
     *         if $value is not accepted for the property $name
     * @param string $name
     * @param mixed $value
     * @return void
     * @ignore
     */
    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'transportMethod':
                if ( !(  in_array($value, array( 'file', 'smtp', 'sendmail' ) ) ) )
                {
                    throw new ezcBaseValueException( 'transportMethod', $value, 'file, smtp, sendmail' );
                }
                $this->properties[$name] = $value;
                break;

            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
    }

    /**
     * Returns the value of the property $name.
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the property $name does not exist
     *
     * @param string $name
     * @return mixed
     * @ignore
     */
    public function __get( $name )
    {
        switch ( $name )
        {
            case 'transportMethod':
                return $this->properties[$name];
            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
    }

    /**
     * Returns true if the property $name is set, otherwise false.
     *
     * @param string $name
     * @return boolean
     * @ignore
     */
    public function __isset( $name )
    {
        switch ( $name )
        {
            case 'transportMethod':
                return isset( $this->properties[$name] );

            default:
                return false;
        }
    }

}

?>