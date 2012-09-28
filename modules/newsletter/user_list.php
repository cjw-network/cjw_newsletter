<?php
/**
 * File user_list.php
 *
 * @copyright Copyright (C) 2007-2010 CJW Network - Coolscreen.de, JAC Systeme GmbH, Webmanufaktur. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package cjw_newsletter
 * @subpackage modules
 * @filesource
 */

$module = $Params['Module'];
$templateFile = 'design:newsletter/user_list.tpl';

$tpl = eZTemplate::factory();

$http = eZHTTPTool::instance();
$db = eZDB::instance();

$searchUserEmail = false;

if( $http->hasVariable( 'SearchUserEmail' ) )
{
    $searchUserEmail = trim( $db->escapeString( $http->variable( 'SearchUserEmail' ) ) );
}

$viewParameters = array( 'offset' => 0,
                         'namefilter' => '' );

$searchParameters = array( 'search_user_email' => $searchUserEmail );

$userParameters = $Params['UserParameters'];
$viewParameters = array_merge( $viewParameters, $userParameters );
$viewParameters = array_merge( $viewParameters, $searchParameters );

$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();

$Result['content'] = $tpl->fetch( $templateFile );
$Result['path'] =  array( array( 'url'  => 'newsletter/index',
                                 'text' => ezpI18n::tr( 'cjw_newsletter/path', 'Newsletter' ) ),
                          array( 'url'  => false,
                                 'text' => ezpI18n::tr( 'cjw_newsletter/user_list', 'Users' ) ) );

?>
