<?php
/**
 * File subscripe_infomail.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();

//  ezuser, anonym oder per hash

$templateFile = "design:newsletter/subscribe_infomail.tpl";

$warningArr = array();

if ( $module->isCurrentAction( 'SubscribeInfoMail' ) )
{

    if ( $module->hasActionParameter( 'Email' ) )
    {
        $backUrl = '/';

        if ( $module->hasActionParameter( 'BackUrl' ) )
        {
            $backUrl = $module->actionParameter( 'BackUrl' );
        }

        // Wenn email ok
        // Wenn user existiert zu email
        // infomail schicken
        $email = $module->actionParameter( 'Email' );

        if ( eZMail::validate( $email ) )
        {

            // ansonsten tue nix
            $newsletterUser = CjwNewsletterUser::fetchByEmail( $email );

            if ( $newsletterUser )
            {
                $sendResult = $newsletterUser->sendSubcriptionInformationMail();

            }
            // immer erfolgstemplate zeigen auch wenn email falsch ist
            $tpl->setVariable( 'email_input', $email );
            $tpl->setVariable( 'back_url_input', $backUrl );

            $templateFile = "design:newsletter/subscribe_infomail_success.tpl";
        }
        else
        {
            $warningArr[] = array( 'field_key' => ezpI18n::tr( 'cjw_newsletter/subscribe_infomail','email'),
                                   'message' => ezpI18n::tr( 'cjw_newsletter/subscribe_infomail', 'Please input a valid e-mail address!' ) );
        }
    }

}

$tpl->setVariable( 'warning_array', $warningArr );


$Result = array();
//$Result['content'] = $tpl->fetch( "design:newsletter/subscribe.tpl" );

$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'cjw_newsletter/subscribe_info', 'Get subscribe information' ) ) );


?>
