<?php
/**
 * File user_list.php
 *
 * @copyright Copyright (C) 2007-2012 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];
$templateFile = 'design:newsletter/user_list.tpl';

require_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

$tpl  = templateInit();
$http = eZHTTPTool::instance();
$db   = eZDB::instance();

$searchUserEmail = false;
$offset          = 0;
$limit           = 10;

$filterArray = array();
//
// filter examples
//
//$filterArray[] = array( 'cjwnl_user.email' => array( 'OR', array( 'like', '%@%' ), array( 'like', '%abc@%' ) ) );
//$filterArray[] = array( 'cjwnl_user.email' => array( 'OR', array( 'AND', 'woldt', 'acd'  ), array( 'like', '%abc@%' ) ) );
//$filterArray[] = array( 'cjwnl_user.last_name' => array( array( 'woldt', 'acd' ) ) );
//$filterArray[] = array( 'cjwnl_user.last_name' => array( 'AND', 'woldt', 'acd'  ) );
//$filterArray[] = array( 'cjwnl_subscription.list_contentobject_id' => array(  array( 132 , 109 ) ) );
//$filterArray[] = array( 'cjwnl_subscription.status' => CjwNewsletterSubscription::STATUS_APPROVED );
//$filterArray[] = array( 'cjwnl_user.email' =>  array( 'like', '%@%.de' ) );

// get wanted user email and filter by itself
if( $http->hasVariable( 'SearchUserEmail' ) )
{
    $searchUserEmail = trim( $db->escapeString( $http->variable( 'SearchUserEmail' ) ) );
    $filterArray[]   = array( 'cjwnl_user.email' =>  array( 'like', $searchUserEmail ) );
}

// AND - all filter should match
// OR - 1 one the filter should be match
// AND-NOT - none of the filter should be matched

$userListSearch = CjwNewsletterUser::fetchUserListByFilter( $filterArray,
                                                            $limit,
                                                            $offset );

$tpl->setVariable( 'user_list', $userListSearch );
$tpl->setVariable( 'user_list_count', count( $userListSearch ) );

$viewParameters = array( 'offset'     => 0,
                         'namefilter' => '' );

$searchParameters = array( 'search_user_email' => $searchUserEmail );

$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( $viewParameters, $userParameters );
$viewParameters = array_merge( $viewParameters, $searchParameters );

$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezi18n( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => false,
                                 'text' => ezi18n( 'cjw_newsletter/user_list', 'Users' ) ) );

?>
