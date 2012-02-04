<?php
/**
 * File subscription_view.php
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
$tpl = eZTemplate::factory();

$templateFile = 'design:newsletter/subscription_view.tpl';

$subscriptionId = (int) $Params['SubscriptionId'];
$subscriptionObject = CjwNewsletterSubscription::fetch( $subscriptionId );

if( !is_object( $subscriptionObject ) )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$message = '';

if( $http->hasVariable( 'SubscriptionApproveButton' ) )
{
    $subscriptionObject->approveByAdmin();
    $message = ezpI18n::tr( 'cjw_newsletter/subscription_view','Subscription successfully approved!' );
}

if( $http->hasVariable( 'SubscriptionRemoveButton' ) )
{
    $subscriptionObject->removeByAdmin();
    $message = ezpI18n::tr( 'cjw_newsletter/subscription_view','Subscription successfully removed!' );
}

$viewParameters = array();
if( is_array( $Params['UserParameters'] ) )
{
    $viewParameters = array_merge( $viewParameters, $Params['UserParameters'] );
}

$listObject = $subscriptionObject->attribute( 'newsletter_list' );


$listNodeId = $listObject->attribute( 'main_node_id' );
$listNode = eZContentObjectTreeNode::fetch( $listNodeId );
$systemNode = $listNode->attribute( 'parent' );

$tpl->setVariable( 'newsletter_list_node', $listNode );
$tpl->setVariable( 'newsletter_list_node_id', $listNodeId );

$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'subscription', $subscriptionObject );

$tpl->setVariable( 'message', $message );

$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );

$name = $subscriptionId;
$newsletter_user = $subscriptionObject->attribute( 'newsletter_user' );
if ( is_object( $newsletter_user) )
{
    $name = $newsletter_user->attribute( 'email' );
}


$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezpI18n::tr( 'cjw_newsletter/path', 'Newsletter' ) ),

                          array( 'url'  => $systemNode->attribute( 'url_alias' ),
                                 'text' => $systemNode->attribute( 'name' ) ),

                          array( 'url'  => $listNode->attribute( 'url_alias' ),
                                 'text' => $listNode->attribute( 'name' ) ),

                          array( 'url'  => 'newsletter/subscription_list/' .$listNodeId,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/subscription_list', 'Subscriptions' ) ),

                          array( 'url'  => false,
                                 'text' => $name ) );

?>
