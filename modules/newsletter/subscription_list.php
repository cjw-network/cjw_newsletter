<?php
/**
 * File subscription_list.php
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

$templateFile = "design:newsletter/subscription_list.tpl";

$nodeId = (int) $Params['NodeId'];

$node = eZContentObjectTreeNode::fetch( $nodeId );
if( !is_object($node ))
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$viewParameters = array( 'offset' => 0,
                         'namefilter' => '' );

if( is_array( $Params['UserParameters'] ) )
{
    $viewParameters = array_merge( $viewParameters, $Params['UserParameters'] );
}

// Parameter der View als Array dem Template übergeben
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'node', $node );

$systemNode = $node->attribute( 'parent' );

$Result = array();

$Result['node_id'] = $nodeId;
$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezi18n( 'cjw_newsletter/path', 'Newsletter' ) ),

                          array( 'url'  => $systemNode->attribute( 'url_alias' ),
                                 'text' => $systemNode->attribute( 'name' ) ),

                          array( 'url'  => $node->attribute( 'url_alias' ),
                                 'text' => $node->attribute( 'name' ) ),

                          array( 'url'  => false,
                                 'text' => ezi18n( 'cjw_newsletter/subscription_list', 'Subscriptions' ) ) );


?>
