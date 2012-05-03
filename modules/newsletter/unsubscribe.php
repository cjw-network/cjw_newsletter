<?php
/**
 * File unsubscribe.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$module = $Params['Module'];

$http = eZHTTPTool::instance();
$tpl = templateInit();
$subscription = CjwNewsletterSubscription::fetchByHash( $Params['Hash'] );

$ini = eZINI::instance( 'cjw_newsletter.ini' );

if ( !$subscription )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$newsletterUser = $subscription->attribute( 'newsletter_user' );
if ( !is_object( $newsletterUser ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
if ( $newsletterUser->isOnBlacklist() )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if( $subscription->isRemoved() )
{
    $tplTemplate = 'design:newsletter/unsubscribe_already_done.tpl';
}
elseif ( $ini->variable( 'SubscriptionSetting', 'RequireUnsubscribeConfirmation' ) === 'disabled' || $module->isCurrentAction( 'Unsubscribe' ) )
{
    $unsubscribeResult = $subscription->unsubscribe();
    $tpl->setVariable( 'unsubscribe_result', $unsubscribeResult );


    $tplTemplate = 'design:newsletter/unsubscribe_success.tpl';
}
else if ( $module->isCurrentAction( 'Cancel' ) )
{
    $cancelUri = '/';
    if ( $module->hasActionParameter( 'CancelUri' ) )
    {
        $cancelUri = $module->actionParameter( 'CancelUri' );
    }

    $module->redirectTo( $cancelUri );
    // echo $cancelUrl;
}
else
{
    $tplTemplate = 'design:newsletter/unsubscribe.tpl';
}

$tpl->setVariable( 'newsletter_user', $newsletterUser );
$tpl->setVariable( 'subscription', $subscription );

$Result = array();
$Result['content'] = $tpl->fetch( $tplTemplate );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'cjw_newsletter/unsubscribe', 'Unsubscribe' ) ) );


?>
