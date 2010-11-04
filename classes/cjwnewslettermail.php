<?php
/**
 * File containing the CjwNewsletterMail class
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
class CjwNewsletterMail
{

    /**
     *
     * @var string  CRLF - windows
     */
    const HEADER_LINE_ENDING_CRLF = "\r\n";
    /**
     *
     * @var string  CR   - mac
     */
    const HEADER_LINE_ENDING_CR = "\r";
    /**
     *
     * @var string  LF   - UNIX-MACOSX
     */
    const HEADER_LINE_ENDING_LF = "\n";

    /**
     *
     * @var string
     */
    protected $transportMethod = 'file';

    /**
     *
     * @var array assosiative array for additional email Header with some variables
     *      for better bounce parsing
     *      for example
     *      array['X-CJWNL-Edition']=lsjdfo13uru32s
     */
    private $ExtraEmailHeaderItemArray = array();

    /**
     * which header line ending should be used for mail creation
     * @var string
     */
    private $HeaderLineEnding = null;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->setHeaderLineEndingFromIni();
        $this->resetExtraMailHeaders();
        //include_once( dirname( __FILE__ ).'/CjwNewsletterErrorHandler.php' );
    }

    /**
     * Generated all outputformats
     *
     * @param unknown_type $objectVersion
     * @return array
     */
    function getAllOutputFormatTextByContentObjectVersion( $objectVersion, $forceSettingImageIncludeTo = -1 )
    {
        $outputFormatTextArray = array();
        $dataMap = $objectVersion->attribute('data_map');
        $editionAttribute = $dataMap['newsletter_edition'];
        $editionAttributeContent = $editionAttribute->attribute('content');
        $listAttributeContent = $editionAttributeContent->attribute('list_attribute_content');
        $outputFormatArray = $listAttributeContent->attribute('output_format_array');
        $mainSiteAccess = $listAttributeContent->attribute('main_siteaccess');
        $skinName = $listAttributeContent->attribute('skin_name');
        $editionContentObjectId = $objectVersion->attribute('contentobject_id');
        $versionId = $objectVersion->attribute('version');
        $emailSender = $listAttributeContent->attribute('email_sender');
        $emailSenderName = $listAttributeContent->attribute('email_sender_name');

        foreach ( $outputFormatArray as $outputFormatId => $outputName )
        {
            $newsletterContentArray = CjwNewsletterEdition::getOutput( $editionContentObjectId, $versionId, $outputFormatId, $mainSiteAccess, $skinName, $forceSettingImageIncludeTo );
            $newsletterContentArray['email_sender'] = $emailSender;
            $newsletterContentArray['email_sender_name'] = $emailSenderName;

            $outputFormatTextArray[ $outputName ] = $newsletterContentArray;
        }
        return $outputFormatTextArray;
    }

    /**
     * Send testnewsletter to one email address
     *
     * @param unknown_type $editonContentObjectVersion
     * @param unknown_type $emailReceiver
     * @param $forceSettingImageIncludeTo
     *          -1 - use settings from ini to send a testmail
     *           0 - will not include images in mail ignoring ini setting
     *           1 - will include all images in mail ignoring ini setting
     * @return unknown_type
     */
    function sendNewsletterTestMail( $editonContentObjectVersion, $emailReceiver, $forceSettingImageIncludeTo = -1 )
    {

        // read from ini if inlude images into email message
        //$imageInclude = CjwNewsletterEdition::imageIncludeIsEnabled();

        // generate all newsletter versions
        $outputFormatTextArray = $this->getAllOutputFormatTextByContentObjectVersion( $editonContentObjectVersion, $forceSettingImageIncludeTo );
        $sendResult = array();
        $this->setTransportMethodPreviewFromIni();

        // send one mail for every version
        foreach ( $outputFormatTextArray as $outputFormat )
        {
            $result = $this->sendEmail( $outputFormat['email_sender'],
                                        $outputFormat['email_sender_name'],
                                        $emailReceiver,
                                        $emailReceiverName = 'Tester ',
                                        $outputFormat['subject'],
                                        $outputFormat['body'],
                                        $isPreview = true
                                         );

            $sendResult[ $outputFormat['output_format'] ] = $result;
        }
        return $sendResult;
    }

    /**
     * Mainfunction for mail send
     *
     * @param unknown_type $emailSender
     * @param unknown_type $emailSenderName
     * @param unknown_type $emailReceiver
     * @param unknown_type $emailReceiverName
     * @param unknown_type $emailSubject
     * @param unknown_type $emailBodyArray
     * @param boolean $isPreview
     * @param string $emailCharset
     * @return array
     */
    public function sendEmail( $emailSender,
                        $emailSenderName,
                        $emailReceiver,
                        $emailReceiverName,
                        $emailSubject,
                        $emailBodyArray,
                        $isPreview = false,
                        $emailCharset = 'utf-8'
                         )
    {

        $transportMethod = $this->transportMethod;
        //$mail = new ezcMailComposer();
        $mail = new CjwNewsletterMailComposer();
        $mail->charset = $emailCharset;
        $mail->subjectCharset = $emailCharset;
        // from and to addresses, and subject
        $mail->from = new ezcMailAddress( trim( $emailSender ), $emailSenderName );
        // returnpath for email bounces
        $mail->returnPath = new ezcMailAddress( trim( $emailSender ) );

        if ( $isPreview )
        {
            $explodeReceiverArr = explode( ';', $emailReceiver );
            foreach ( $explodeReceiverArr as $index => $receiver )
            {
                // check if email
                if ( $receiver != '' )
                {
                    $mail->addTo( new ezcMailAddress( trim( $receiver ), 'NL Test Receiver'. $index ) );
                }
            }
        }
        else
        {
            $mail->addTo( new ezcMailAddress( trim( $emailReceiver ), $emailReceiverName ) );
        }

        if ( array_key_exists( 'html', $emailBodyArray ) == false )
            $emailBodyArray['html'] = '';
        if ( array_key_exists( 'text', $emailBodyArray ) == false )
            $emailBodyArray['text'] = '';

        $mail->subject = $emailSubject;
        if ( $emailBodyArray['html'] == '' )
        {
            // tue nix - da kein html da ist
        }
        else
        {
            $mail->htmlText = $emailBodyArray['html'];
        }

        // body: plain
        // $mail->plainText = "Here is the text version of the mail.";
        if ( $emailBodyArray['text'] == '' )
        {
            // $mail->plainText = "Text version of this mail does not exists.";
        }
        else
        {
            $mail->plainText = $emailBodyArray['text'];
        }

        $emailContentType = '';
        if ( $emailBodyArray['html'] != '' && $emailBodyArray['text'] != '' )
        {
            $emailContentType = 'multipart/alternative';
        }
        else if ( $emailBodyArray['html'] != '' )
        {
            $emailContentType = 'text/html';
        }
        elseif ( $emailBodyArray['text'] != '' )
        {
            $emailContentType = 'text/plain';
        }


        // http://ezcomponents.org/docs/api/latest/introduction_Mail.html#mta-qmail
        // HeaderLineEnding=auto
        // CRLF - windows - \r\n
        // CR - mac - \r
        // LF - UNIX-MACOSX - \n
        // default LF
        //ezcMailTools::setLineBreak( "\n" );
        ezcMailTools::setLineBreak( $this->HeaderLineEnding );

        // set 'x-cjwnl-' mailheader
        foreach( $this->ExtraEmailHeaderItemArray as $key => $value )
        {
            $mail->setHeader( $key, $value );
        }

        $mail->build();
        $transport = new CjwNewsletterTransport( $transportMethod );
        $sendResult = $transport->send( $mail );

        $emailResult = array('send_result' => $sendResult,
                             'email_sender' => $emailSender,
                             'email_receiver' => $emailReceiver,
                             'email_subject' => $emailSubject,
                             'email_content_type' => $emailContentType,
                             'email_charset' => $emailCharset,
                             'transport_method' => $transportMethod );
        // ok
        if ( $sendResult )
        {
            CjwNewsletterLog::writeInfo( 'email send ok', 'CjwNewsletterMail', 'sendEmail', $emailResult );
        }
        else
        {
            CjwNewsletterLog::writeError( 'email send failed', 'CjwNewsletterMail', 'sendEmail', $emailResult );
        }
        // $LogFile->write( $message, $logName, $logFolder );
        return $emailResult;
    }

    /**
     *
     * @param unknown_type $emailSender
     * @param unknown_type $emailReciever
     * @param unknown_type $emailSubject
     * @param unknown_type $emailBody
     * @param string $emailContentType
     * @param string $emailCharset
     * @return array
     */
    function sendEmailWithEz( $emailSender, $emailReciever, $emailSubject, $emailBody, $emailContentType = 'text/html', $emailCharset = 'utf-8' )
    {
        $mail = new eZMail();
        $redirectURL = false;
        $mail->setReceiver( trim( $emailReciever ) );
        $mail->setSender( trim( $emailSender )  );
        $mail->setSubject( $emailSubject );
        $mail->setBody( $emailBody );

        // $mail->setContentType( $type = 'text/html', $charset = false, $transferEncoding = false, $disposition = false, $boundary = false);
        $mail->setContentType( $emailContentType, $emailCharset, $transferEncoding = false, $disposition = false, $boundary = false);
        $emailResultArray = array();

        $emailResult = eZMailTransport::send( $mail );
        $emailResult = array('email_result' => $emailResult, 'email_sender' => $emailSender, 'email_reciever' => $emailReciever, 'email_subject' => $emailSubject, 'email_content_type' => $emailContentType, 'email_charset' => $emailCharset );

        if ( $mailResult === true )
        {
            $message = "send - " . $receiver['email'] . " - " . $receiver['name'];
        }
        else
        {
            $message = "not send - " . $receiver['email'] . " - " . $receiver['name'];
        }
        return $emailResult;
    }

    /**
     * Read ini and set transport
     *
     * @return unknown_type
     */
    function setTransportMethodPreviewFromIni()
    {
        $cjwNewsletterINI = eZINI::instance( 'cjw_newsletter.ini' );
        $transportMethodPreview = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'TransportMethodPreview' );
        $this->transportMethod = $transportMethodPreview;

        return $this->transportMethod;
    }

     /**
     * Read ini and set transport
     *
     * @return unknown_type
     */
    function setTransportMethodDirectlyFromIni()
    {
        $cjwNewsletterINI = eZINI::instance( 'cjw_newsletter.ini' );
        $transportMethodDirectly = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'TransportMethodDirectly' );
        $this->transportMethod = $transportMethodDirectly;

        return $this->transportMethod;
    }

     /**
     * Read ini and set transport
     *
     * @return unknown_type
     */
    function setTransportMethodCronjobFromIni()
    {
        $cjwNewsletterINI = eZINI::instance( 'cjw_newsletter.ini' );
        $transportMethodCronjob = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'TransportMethodCronjob' );
        $this->transportMethod = $transportMethodCronjob;
        return $this->transportMethod;
    }

    /**
     * read header line ending settings from cjw_newsletter.ini
     *     *
     * http://ezcomponents.org/docs/api/latest/introduction_Mail.html#mta-qmail
     * @return string headerlineending
     */
    private function setHeaderLineEndingFromIni()
    {
        $cjwNewsletterINI = eZINI::instance( 'cjw_newsletter.ini' );
        $headerLineEndingIni = $cjwNewsletterINI->variable( 'NewsletterMailSettings', 'HeaderLineEnding' );

        switch ( $headerLineEndingIni )
        {
            case 'CRLF':
                $this->HeaderLineEnding = self::HEADER_LINE_ENDING_CRLF;
            break;

            case 'CR':
                $this->HeaderLineEnding = self::HEADER_LINE_ENDING_CR;
            break;

            case 'LF':
                $this->HeaderLineEnding = self::HEADER_LINE_ENDING_LF;
            break;

            // TODO choose automatically the right settings
            case 'auto':
                $this->HeaderLineEnding = self::HEADER_LINE_ENDING_LF;
            break;

            // default line ending \n
            default:
                $this->HeaderLineEnding = self::HEADER_LINE_ENDING_LF;
            break;
        }

        return $this->HeaderLineEnding;
    }

    /**
     * reset  $this->extraEmailHeaderItemArray and set version number
     */
    public function resetExtraMailHeaders()
    {
        $this->extraEmailHeaderItemArray = array();
        //$this->setExtraMailHeader( 'version', '1.0.0alpha' );
        $this->setExtraMailHeader( 'version', cjw_newsletterInfo::SOFTWARE_VERSION );
    }

    /**
     * used by Newletter edition preview and newsletter cronjob process
     *
     * @param CjwNewsletterUser $newsletterUser
     * @return boolean
     */
    public function setExtraMailHeadersByNewsletterUser( $newsletterUser )
    {
        if( $newsletterUser instanceof CjwNewsletterUser )
        {
            $this->setExtraMailHeader( 'receiver', $newsletterUser->attribute('email') );
            $this->setExtraMailHeader( 'user', $newsletterUser->attribute('hash') );
        }
        else
        {
            return false;
        }
    }

    /**
     * used by newsletter cronjob process
     *
     * @param CjwNewsletterUser $newsletterUser
     * @return boolean
     */
    public function setExtraMailHeadersByNewsletterSendItem( $newsletterEditionSendItem )
    {
        if( $newsletterEditionSendItem instanceof CjwNewsletterEditionSendItem )
        {
            // nl user header setzen
            $this->setExtraMailHeadersByNewsletterUser( $newsletterEditionSendItem->attribute( 'newsletter_user_object' ) );
            $this->setExtraMailHeader( 'senditem', $newsletterEditionSendItem->attribute( 'hash' ) );

            // unsubscribe hash
            $subscriptionObject = $newsletterEditionSendItem->attribute( 'newsletter_subscription_object' );
            $this->setExtraMailHeader( 'subscription', $subscriptionObject->attribute( 'hash' ) );
        }
        else
        {
            return false;
        }
    }

    /**
     * Set a new extra mailheader item
     *
     * setExtraMailHeader( 'Version', '1.0.0' ) will add the following mail header item
     *
     * X-CJWNl-Version : 1.0.0
     *
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function setExtraMailHeader( $name, $value )
    {
        $this->ExtraEmailHeaderItemArray[ 'x-cjwnl-'. $name ] = (string) $value;
        return true;
    }



}

?>