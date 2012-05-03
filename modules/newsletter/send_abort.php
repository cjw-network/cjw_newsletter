<?php
/**
 * File send_abort.php
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

$module = $Params["Module"];
$http = eZHTTPTool::instance();

$viewParameters = array();
$message_warning = '';
$message_feedback = '';

if( isSet( $Params['EditionSendId'] ) )
{
    $editionSendId = (int) $Params['EditionSendId'];
}
else
{
    $editionSendId = null;
}

$tpl = eZTemplate::factory();
$editionSendObject = CjwNewsletterEditionSend::fetch( $editionSendId );

if ( !is_object( $editionSendObject ) )
{
    // return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $editionSendObject->attribute('status') == CjwNewsletterEditionSend::STATUS_MAILQUEUE_PROCESS_FINISHED )
{
    $message_warning = ezpI18n::tr( 'cjw_newsletter/send_abort', 'Send out process is finished, can not abort anymore!', null , array(  ) );
}
elseif ( $editionSendObject->attribute('status') == CjwNewsletterEditionSend::STATUS_ABORT )
{
    $message_warning = ezpI18n::tr( 'cjw_newsletter/send_abort', 'Send out process was already aborted!', null , array(  ) );
}
else
{
    if ( $http->hasVariable( 'AbortSendOutButton' ) )
    {
        $abortResult = $editionSendObject->abortAllSendItems();

        if ( $abortResult == true )
            $message_feedback = ezpI18n::tr( 'cjw_newsletter/send_abort', 'Abort successfull', null , array(  ) );
        else
            $message_feedback =  ezpI18n::tr( 'cjw_newsletter/send_abort', 'Abort not successfull', null , array(  ) );

        $tpl->setVariable( 'send_abort_result', $abortResult );
    }
}

$editionContentObject = eZContentObject::fetch( $editionSendObject->attribute( 'edition_contentobject_id' ) );
$main_node_id = $editionContentObject->attribute( 'main_node_id' );

$editionNode = eZContentObjectTreeNode::fetch( $main_node_id );
$listNode = $editionNode->attribute( 'parent' );
$systemNode = $listNode->attribute( 'parent' );

if ( $http->hasVariable( 'CancelButton' ) )
{
    $redirectUri = "content/view/full/$main_node_id";
    return $module->redirectTo( $redirectUri );
}
$Result = array();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'edition_send_id', $editionSendId );
$tpl->setVariable( 'edition_send_object', $editionSendObject );

$has_message = false;
$tpl->setVariable( 'message_warning', $message_warning );
$tpl->setVariable( 'message_feedback', $message_feedback );
if( $message_warning !== '' || $message_feedback !== '' )
{
    $has_message = true;
}
$tpl->setVariable( 'has_message', $has_message );


$Result['content'] = $tpl->fetch( "design:newsletter/send_abort.tpl" );
$Result['path'] = array( array( 'url' => false,
                                    'text' => ezpI18n::tr('cjw_newsletter/send', 'Newsletter Send') )
                              );

$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezpI18n::tr( 'cjw_newsletter/path', 'Newsletter' ) ),

                          array( 'url'  => $systemNode->attribute( 'url_alias' ),
                                 'text' => $systemNode->attribute( 'name' ) ),

                          array( 'url'  => $listNode->attribute( 'url_alias' ),
                                 'text' => $listNode->attribute( 'name' ) ),

                          array( 'url'  => $editionNode->attribute( 'url_alias' ),
                                 'text' => $editionNode->attribute( 'name' ) ),

                          array( 'url'  => false,
                                 'text' => ezpI18n::tr('cjw_newsletter/send_abort', 'Abort sent out process') ) );



?>
